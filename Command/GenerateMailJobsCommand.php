<?php

namespace Ibrows\Bundle\NewsletterBundle\Command;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManager;
use Ibrows\Bundle\NewsletterBundle\Model\Job\MailJob;
use Ibrows\Bundle\NewsletterBundle\Model\Mandant\MandantInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Mandant\MandantManager;
use Ibrows\Bundle\NewsletterBundle\Model\Newsletter\NewsletterInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Subscriber\SubscriberGenderTitleInterface;
use Ibrows\Bundle\NewsletterBundle\Renderer\Bridge\RendererBridge;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateMailJobsCommand extends ContainerAwareCommand
{
    /**
     * @var MandantManager
     */
    private $mm;

    /**
     * @var string
     */
    private $newsletterClass;

    /**
     * @var string
     */
    private $mailJobClass;

    protected function configure()
    {
        $this
            ->setName('ibrows:newsletter:job:mail:generate')
            ->setDescription('Generates the mail jobs for maximum one newsletter per mandant')
            ->addOption(
                'mandant',
                null,
                InputOption::VALUE_OPTIONAL,
                'The mandant to use'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->mm = $this->getContainer()->get('ibrows_newsletter.mandant_manager');
        $this->newsletterClass = $this->getContainer()->getParameter('ibrows_newsletter.classes.model.newsletter');
        $this->mailJobClass = $this->getContainer()->getParameter('ibrows_newsletter.classes.model.mailjob');

        if ($mandantName = $input->getOption('mandant')) {
            $manager = $this->mm->getObjectManager($mandantName);
            if ($newsletter = $this->getReadyNewsletter($manager)) {
                try {
                    $this->generateMailJobs($manager, $this->mm->get($mandantName), $newsletter);
                } catch (\Exception $e) {
                    $this->setNewsletterError($manager, $newsletter->getId(), $e);
                    throw $e;
                }
            }
            return;
        }

        foreach ($this->mm->getMandants() as $name => $description) {
            $manager = $this->mm->getObjectManager($name);
            if ($newsletter = $this->getReadyNewsletter($manager)) {
                try {
                    $this->generateMailJobs($manager, $this->mm->get($name), $newsletter);
                } catch (\Exception $e) {
                    $this->setNewsletterError($manager, $newsletter->getId(), $e);
                    throw $e;
                }
            }
        }
    }

    /**
     * @param ObjectManager $manager
     * @return NewsletterInterface
     * @throws ConnectionException
     */
    private function getReadyNewsletter(ObjectManager $manager)
    {
        /** @var ObjectRepository $qb */
        $repo = $manager->getRepository($this->newsletterClass);

        /** @var NewsletterInterface $newsletter */
        $newsletter = $repo->findOneBy(array(
            'status' => NewsletterInterface::STATUS_READY
        ));

        if (!$newsletter) {
            return null;
        }

        $newsletter->setStatus(NewsletterInterface::STATUS_WORKING);

        $manager->persist($newsletter);
        $manager->flush();

        return $newsletter;
    }

    /**
     * @param ObjectManager $objectManager
     * @param MandantInterface $mandant
     * @param NewsletterInterface $newsletter
     */
    private function generateMailJobs(ObjectManager $objectManager, MandantInterface $mandant, NewsletterInterface $newsletter)
    {
        $sendSettings = $newsletter->getSendSettings();
        $mailjobClass = $this->mailJobClass;

        $rendererManager = $this->getContainer()->get('ibrows_newsletter.renderer_manager');
        $rendererName = $mandant->getRendererName();
        $bridge = $this->getRendererBridge();

        $subscribers = $newsletter->getSubscribers();
        $count = 1;
        $receiverMails = array();

        foreach ($subscribers as $subscriber) {
            $receiverMail = $subscriber->getEmail();

            // If subscriber already in list - do not add again
            if (in_array($receiverMail, $receiverMails)) {
                continue;
            }
            $receiverMails[] = $receiverMail;

            if ($count % $sendSettings->getInterval() === 0) {
                $time = $sendSettings->getStarttime();
                $time->modify('+ 1 minutes');
                $sendSettings->setStarttime(clone $time);
            }

            $body = $rendererManager->renderNewsletter(
                $rendererName,
                $bridge,
                $newsletter,
                $mandant,
                $subscriber
            );

            /* @var $mailjob MailJob */
            $mailjob = new $mailjobClass($newsletter, $sendSettings);
            $mailjob->setBody($body);
            $mailjob->setToMail($receiverMail);

            if ($subscriber instanceof SubscriberGenderTitleInterface) {
                $mailjob->setToName($subscriber->getFirstname() . ' ' . $subscriber->getLastname());
            }

            $mailjob->setStatus(MailJob::STATUS_READY);
            $objectManager->persist($mailjob);
            ++$count;

            if ($count % 200 == 0) {
                $objectManager->flush();
                $objectManager->clear();
            };
        }

        $objectManager->flush();

        $this->setNewsletterCompleted($objectManager, $newsletter->getId());
    }

    /**
     * @return RendererBridge
     */
    private function getRendererBridge()
    {
        $container = $this->getContainer();
        return $container->get($container->getParameter('ibrows_newsletter.serviceid.rendererbridge'));
    }

    /**
     * @param EntityManager $manager
     * @param int $newsletterId
     */
    private function setNewsletterCompleted(EntityManager $manager, $newsletterId)
    {
        $qb = $manager->getRepository($this->newsletterClass)->createQueryBuilder('n');
        $qb
            ->update()
            ->set('n.status', ':status')
            ->where('n.id = :newsletterId')
            ->setParameter('status', NewsletterInterface::STATUS_COMPLETED)
            ->setParameter('newsletterId', $newsletterId)
            ->getQuery()->execute();
    }

    /**
     * @param EntityManager $manager
     * @param int $newsletterId
     * @param \Exception $e
     */
    private function setNewsletterError(EntityManager $manager, $newsletterId, \Exception $e)
    {
        $qb = $manager->getRepository($this->newsletterClass)->createQueryBuilder('n');
        $qb
            ->update()
            ->set('n.status', ':status')
            ->set('n.error', ':error')
            ->where('n.id = :newsletterId')
            ->setParameter('status', NewsletterInterface::STATUS_ERROR)
            ->setParameter('error', $e->getCode() . ': ' . $e->getMessage())
            ->setParameter('newsletterId', $newsletterId)
            ->getQuery()->execute();
    }
}
