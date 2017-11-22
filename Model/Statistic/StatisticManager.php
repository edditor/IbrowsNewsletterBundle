<?php
namespace Ibrows\Bundle\NewsletterBundle\Model\Statistic;

use Ibrows\Bundle\NewsletterBundle\Model\Subscriber\SubscriberLocaleInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Subscriber\SubscriberGenderTitleInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Subscriber\SubscriberInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Newsletter\NewsletterInterface;

abstract class StatisticManager implements StatisticManagerInterface
{

    protected $mandantName;
    protected $readLogClass;
    protected $sendLogClass;
    protected $unsubscribeLogClass;

    public function __construct($mandantName, $readLogClass, $sendLogClass, $unsubscribeLogClass)
    {
        $this->mandantName = $mandantName;
        $this->readLogClass = $readLogClass;
        $this->sendLogClass = $sendLogClass;
        $this->unsubscribeLogClass = $unsubscribeLogClass;
    }

    protected function createNewsletterReadLog(NewsletterInterface $newsletter, SubscriberInterface $subscriber, $message)
    {
        return $this->createNewsletterLog($this->readLogClass, $newsletter, $subscriber, $message);
    }

    protected function createNewsletterSendLog(NewsletterInterface $newsletter, SubscriberInterface $subscriber, $message)
    {
        return $this->createNewsletterLog($this->sendLogClass, $newsletter, $subscriber, $message);
    }

    protected function createNewsletterUnsubscribeLog(NewsletterInterface $newsletter, SubscriberInterface $subscriber, $message)
    {
        return $this->createNewsletterLog($this->unsubscribeLogClass, $newsletter, $subscriber, $message);
    }

    protected function createNewsletterLog($className, NewsletterInterface $newsletter, SubscriberInterface $subscriber, $message)
    {
        /* @var $log \Ibrows\Bundle\NewsletterBundle\Model\Log\LogInterface */
        $log = new $className();

        $log
            ->setNewsletterId($newsletter->getId())
            ->setSubscriberId($subscriber->getId())
            ->setSubscriberEmail($subscriber->getEmail())
            ->setMessage($message)
            ->setMandantName($this->mandantName)
            ->setSubscriberUseragent(filter_var($_SERVER['HTTP_USER_AGENT'], FILTER_SANITIZE_STRING))
        ;

        if ($subscriber instanceof SubscriberGenderTitleInterface) {
            $log
                ->setSubscriberCompanyname($subscriber->getCompanyname())
                ->setSubscriberFirstname($subscriber->getFirstname())
                ->setSubscriberGender($subscriber->getGender())
                ->setSubscriberLastname($subscriber->getLastname())
                ->setSubscriberTitle($subscriber->getTitle())
            ;
        }

        if ($subscriber instanceof SubscriberLocaleInterface) {
            $log
                ->setSubscriberLocale($subscriber->getLocale())
            ;
        }

        return $log;
    }
}
