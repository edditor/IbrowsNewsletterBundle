<?php

namespace Ibrows\Bundle\NewsletterBundle\Model\Job;

use Ibrows\Bundle\NewsletterBundle\Model\Newsletter\SendSettingsInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Newsletter\NewsletterInterface;

class MailJob extends AbstractJob
{
    protected $subject;
    protected $senderName;
    protected $senderMail;
    protected $returnMail;

    protected $toMail;
    protected $toName;
    protected $body;
    protected $attachments;

    protected $salt;
    public function __construct(NewsletterInterface $newsletter, SendSettingsInterface $sendSettings)
    {
        parent::__construct();

        $this->setSubject($newsletter->getSubject());
        $this->setSenderName($newsletter->getSenderName());
        $this->setSenderMail($newsletter->getSenderMail());
        $this->setReturnMail($newsletter->getReturnMail());

        $this->setSendSettings($sendSettings);
        $this->setScheduled($sendSettings->getStarttime());

        $this->setNewsletterId($newsletter->getId());
        $this->salt = $newsletter->getMandant()->getSalt();
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function getSenderName()
    {
        return $this->senderName;
    }

    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;

        return $this;
    }

    public function getSenderMail()
    {
        return $this->senderMail;
    }

    public function setSenderMail($senderMail)
    {
        $this->senderMail = $senderMail;

        return $this;
    }

    public function getReturnMail()
    {
        return $this->returnMail;
    }

    public function setReturnMail($returnMail)
    {
        $this->returnMail = $returnMail;

        return $this;
    }

    public function getToMail()
    {
        return $this->toMail;
    }

    public function setToMail($toMail)
    {
        $this->toMail = $toMail;

        return $this;
    }

    public function getToName()
    {
        return $this->toName;
    }

    public function setToName($name)
    {
        $this->toName = $name;

        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    public function getAttachments()
    {
        return explode(';', $this->attachments);
    }

    /*
    *@todo change this to entity
    *
    */
    public function setAttachments(array $attachments)
    {
        $this->attachments = implode(';', $attachments);

        return $this;
    }

    public function getSendSettings()
    {
        return $this->sendSettings;
    }

    public function setSendSettings(SendSettingsInterface $settings)
    {
        $this->sendSettings = $settings;

        return $this;
    }
}
