<?php

namespace Ibrows\Bundle\NewsletterBundle\Command;

use Ibrows\Bundle\NewsletterBundle\Model\Mandant\MandantInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class EnableMandantsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ibrows:newsletter:mandants:enable')
            ->setDescription('Insert all mandants given in the config')
            ->addOption('renderer', null, InputOption::VALUE_REQUIRED, 'Service id of the renderer', 'ibrows_newsletter.renderer.twig');
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int|null|void
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mandants = $this->getContainer()->getParameter('ibrows_newsletter.mandants');
        $mandantClassName = $this->getContainer()->getParameter('ibrows_newsletter.classes.model.mandant');

        $mandantManager = $this->getContainer()->get('ibrows_newsletter.mandant_manager');

        foreach ($mandants as $mandantName => $mandantHash) {
            $objectManager = $mandantManager->getObjectManager($mandantName);

            $mandantRepo = $objectManager->getRepository($mandantClassName);

            $mandant = $mandantRepo->findOneBy(
                array(
                    'name' => $mandantName
                )
            );

            if (!$mandant) {
                $output->writeln('Creating mandant <info>' . $mandantName . '</info>');

                /** @var MandantInterface $mandant */
                $mandant = new $mandantClassName();

                $mandant->setName($mandantName);
                $mandant->setHash($mandantHash);
                $mandant->setRendererName($input->getOption('renderer'));

                $objectManager->persist($mandant);
                $objectManager->flush();

                continue;
            }

            $oldHash = $mandant->getHash();
            if ($oldHash != $mandantHash) {
                throw new Exception('Mandant "' . $mandantName . '" is already registered with hash "' . $oldHash . '" - Correct config entry');
            }

            $output->writeln('Mandant <info>' . $mandantName . '</info> already existing');
        }
    }
}
