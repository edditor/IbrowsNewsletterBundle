<?php

namespace Ibrows\Bundle\NewsletterBundle\Command;

use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManager;
use Ibrows\Bundle\NewsletterBundle\Model\Job\JobInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Job\MailJob;
use Ibrows\Bundle\NewsletterBundle\Model\Mandant\MandantManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Exception\UnsupportedException;

class ExecuteMailJobsCommand extends ContainerAwareCommand
{
    const OPTION_LIMIT = 'limit';

    /**
     * @var string
     */
    protected $jobClass;

    /**
     * @var MandantManager
     */
    protected $mm;

    /**
     * @var \DateTime
     */
    protected $now;

    /**
     * @var int
     */
    protected $successCount;

    /**
     * @var int
     */
    protected $errorCount;

    protected function configure()
    {
        $this
            ->setName('ibrows:newsletter:job:mail:send')
            ->setDescription('Executes (sends) a certain amount of ready mailjobs.')
            ->addOption(
                'mandant',
                null,
                InputOption::VALUE_OPTIONAL,
                'The mandant to use'
            )
            ->addOption(
                self::OPTION_LIMIT,
                null,
                InputOption::VALUE_OPTIONAL,
                'The maximal amount of mailjobs to execute',
                25
            );
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int|null|void
     * @throws \LogicException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->jobClass = $this->getContainer()->getParameter('ibrows_newsletter.classes.model.mailjob');
        $this->mm = $this->getContainer()->get('ibrows_newsletter.mandant_manager');
        $this->now = new \DateTime();
        $this->successCount = 0;
        $this->errorCount = 0;

        $mandantName = $input->getOption('mandant');
        if ($mandantName === null) {
            $mandantNames = $this->mm->getMandants();
            foreach ($mandantNames as $name => $description) {
                $this->sendMailJobs($input, $output, $name);
            }
        } else {
            $this->sendMailJobs($input, $output, $mandantName);
        }

        if ($output->getVerbosity() > 1) {
            $output->writeln(sprintf('Mails successfully sent: <info>%s</info>', $this->successCount));
            $output->writeln(sprintf('Mails unsuccessfully sent: <error>%s</error>', $this->errorCount));
        }
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param string          $mandantName
     */
    protected function sendMailJobs(InputInterface $input, OutputInterface $output, $mandantName)
    {
        $limit = $input->getOption(self::OPTION_LIMIT);

        $manager = $this->mm->getObjectManager($mandantName);
        if ($manager instanceof EntityManager) {
            $jobs = $this->getReadyJobsORM($limit, $input, $output, $manager);
        } else {
            throw new UnsupportedException('currently only Doctrine2 ORM is supported');
        }

        $this->sendMails($jobs, $input, $output, $mandantName);
    }

    /**
     * @param int             $limit
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param EntityManager   $manager
     * @return array
     * @throws ConnectionException
     */
    protected function getReadyJobsORM($limit, InputInterface $input, OutputInterface $output, EntityManager $manager)
    {
        $manager->getConnection()->beginTransaction();

        $alias = 'j';
        $qb = $manager->getRepository($this->jobClass)->createQueryBuilder($alias);
        $qb
            ->select("$alias")
            ->where("$alias.status = :status")->setParameter('status', MailJob::STATUS_READY)
            ->andWhere("$alias.scheduled <= :now")->setParameter('now', $this->now)
            ->setMaxResults($limit);

        $jobs = array();
        $iterableResult = $qb->getQuery()->iterate();
        foreach ($iterableResult as $row) {
            /** @var JobInterface $job */
            $job = $row[0];
            $jobs[] = $job;

            $job->setStatus(MailJob::STATUS_WORKING);
        }

        $manager->flush();
        $manager->clear();

        $manager->getConnection()->commit();

        return $jobs;
    }

    /**
     * @param MailJob[]       $jobs
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param string          $mandantName
     */
    protected function sendMails(array $jobs, InputInterface $input, OutputInterface $output, $mandantName)
    {
        $manager = $this->mm->getObjectManager($mandantName);

        if ($output->getVerbosity() > 1) {
            $output->writeln(sprintf('Sending mails for mandant <info>%s</info>', $mandantName));
        }

        // send jobs
        foreach ($jobs as $job) {
            try {
                if ($output->getVerbosity() > 1) {
                    $output->writeln('    Sending mail to <info>' . $job->getToMail() . '</info>');
                }
                $this->getContainer()->get('ibrows_newsletter.mailer')->send($job);
                $job->setStatus(MailJob::STATUS_COMPLETED);
                ++$this->successCount;
            } catch (\Swift_SwiftException $e) {
                if ($output->getVerbosity() > 1) {
                    $output->writeln('        <info>something went wrong.</info>');
                    $output->writeln(sprintf('        <error>%s</error>', $e->getMessage()));
                }
                $job->setStatus(MailJob::STATUS_ERROR);
                $job->setError($e->getMessage() . '||' . $e->getTraceAsString());
                ++$this->errorCount;
            }

            $job->setCompleted(new \DateTime());
            $manager->merge($job);
            $manager->flush();
        }

        if ($output->getVerbosity() > 1) {
            $output->writeln(sprintf('Finished sending mails for mandant <info>%s</info>', $mandantName));
            $output->writeln('');
        }
        $manager->clear();
    }
}
