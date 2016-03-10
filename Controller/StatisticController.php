<?php

namespace Ibrows\Bundle\NewsletterBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Ibrows\Bundle\NewsletterBundle\Model\Job\JobInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Job\MailJob;
use Ibrows\Bundle\NewsletterBundle\Model\Log\Log;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/statistic")
 */
class StatisticController extends AbstractHashMandantController
{
    const TRANSPARENT_GIF = 'R0lGODlhAQABAIAAAP///////yH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';

    /**
     * @Route("/log/read/{mandantHash}/{newsletterHash}/{subscriberHash}", name="ibrows_newsletter_statistic_log_read")
     * @param Request $request
     * @param string  $mandantHash
     * @param string  $newsletterHash
     * @param string  $subscriberHash
     * @return Response
     */
    public function logreadAction(Request $request, $mandantHash, $newsletterHash, $subscriberHash)
    {
        $imageResponse = new Response(
            base64_decode(self::TRANSPARENT_GIF), 200, array(
                'Content-Type' => 'image/gif'
            )
        );

        // if a context is set, its testing or dev --> no log
        if ($request->query->get('context')) {
            return $imageResponse;
        }

        $this->setMandantNameByHash($mandantHash);

        $newsletter = $this->getNewsletterByHash($newsletterHash);
        $subscriber = $this->getSubscriberByHash($newsletter, $subscriberHash);

        // log a read
        $this->addNewsletterReadLog($newsletter, $subscriber, "Newsletter read: logged by " . __METHOD__);

        return $imageResponse;
    }

    /**
     * @Route("/show/{newsletterId}", name="ibrows_newsletter_statistic_show")
     * @param string $newsletterId
     * @return Response
     */
    public function showAction($newsletterId)
    {
        $newsletter = $this->getNewsletterById($newsletterId);

        $statisticManager = $this->getStatisticManager();

        /** @var Log[] $readlogs */
        $readlogs = $statisticManager->getReadLogs(
            array(
                'newsletterId' => $newsletter->getId()
            )
        );

        $objectManager = $this->getObjectManager();

        /** @var EntityRepository $mailJobRepo */
        $mailJobRepo = $objectManager->getRepository($this->getClassManager()->getModel('mailjob'));

        $qb = $mailJobRepo->createQueryBuilder('j');
        $qb
            ->select('partial j.{id, error, status, created, scheduled, completed}')
            ->where('j.newsletterId = ' . (int)$newsletter->getId())
            ->orderBy('j.status', 'ASC');

        /** @var MailJob[] $jobs */
        $jobs = $qb->getQuery()->execute();

        $foundSubscriberIds = array();
        $filteredReadlogs = array_filter(
            $readlogs,
            function ($readlog) use (&$foundSubscriberIds) {
                /** @var Log $readlog */
                $subscriberId = $readlog->getSubscriberId();
                if (!in_array($subscriberId, $foundSubscriberIds)) {
                    $foundSubscriberIds[] = $subscriberId;

                    return true;
                }

                return false;
            }
        );

        $jobPie = array();
        foreach ($jobs as $job) {
            $status = $job->getStatus();
            if (!isset($jobPie[$status])) {
                $jobPie[$status] = 0;
            }
            $jobPie[$status]++;
        }

        $jobStati = array_keys($jobPie);

        $jobsSortedByCompleted = $jobs;
        usort(
            $jobsSortedByCompleted,
            function (MailJob $a, MailJob $b) {
                $dateA = $a->getCompleted() ?: $a->getCreated();
                $dateB = $b->getCompleted() ?: $b->getCreated();

                return $dateA > $dateB;
            }
        );

        $jobLine = array();
        $jobWalkLine = array();
        foreach ($jobsSortedByCompleted as $job) {

            $dateTime = $job->getCompleted() ?: $job->getCreated();
            $date = $dateTime->format('d.m.Y H:i:s');

            foreach ($jobStati as $jobStatus) {
                if (!isset($jobWalkLine[$jobStatus])) {
                    $jobWalkLine[$jobStatus] = 0;
                }

                if (!isset($jobLine[$date])) {
                    $jobLine[$date] = array();
                }

                if ($job->getStatus() == $jobStatus) {
                    $jobLine[$date][$jobStatus] = ++$jobWalkLine[$jobStatus];
                } else {
                    $jobLine[$date][$jobStatus] = $jobWalkLine[$jobStatus];
                }
            }
        }

        $completedAmount = array_key_exists(JobInterface::STATUS_COMPLETED, $jobPie) ? $jobPie[JobInterface::STATUS_COMPLETED] : 0;
        $readAmount = count($filteredReadlogs);
        $unreadAmount = $completedAmount - $readAmount;

        return $this->render(
            $this->getTemplateManager()->getStatistic('show'),
            array(
                'newsletter' => $newsletter,
                'read'       => $readAmount,
                'unread'     => $unreadAmount,
                'jobPie'     => $jobPie,
                'jobLine'    => $jobLine,
                'jobStati'   => $jobStati
            )
        );
    }
}
