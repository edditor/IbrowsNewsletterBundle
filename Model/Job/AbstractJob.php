<?php

namespace Ibrows\Bundle\NewsletterBundle\Model\Job;

use Ibrows\Bundle\NewsletterBundle\Model\Newsletter\NewsletterInterface;

abstract class AbstractJob implements JobInterface
{
    /**
     * @var NewsletterInterface
     */
    protected $newsletter;

    protected $error;
    protected $status;

    protected $created;
    protected $scheduled;
    protected $completed;

    public function __construct()
    {
        $this->created = new \DateTime();
        $this->status = self::STATUS_ONHOLD;
    }

    public function getNewsletterId()
    {
        return $this->newsletter->getId();
    }

    public function getError()
    {
        return $this->error;
    }

    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getScheduled()
    {
        return $this->scheduled;
    }

    public function setScheduled(\DateTime $scheduled)
    {
        $this->scheduled = $scheduled;

        return $this;
    }

    public function getCompleted()
    {
        return $this->completed;
    }

    public function setCompleted(\DateTime $completed)
    {
        $this->completed = $completed;

        return $this;
    }

}
