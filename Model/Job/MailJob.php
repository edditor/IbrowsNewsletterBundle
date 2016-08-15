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

    protected $username;
    protected $password;
    protected $salt;
    protected $host;
    protected $port;
    protected $encryption;
    protected $authMode;

    public function __construct(NewsletterInterface $newsletter, SendSettingsInterface $sendSettings)
    {
        parent::__construct();

        $this->setSubject($newsletter->getSubject());
        $this->setSenderName($newsletter->getSenderName());
        $this->setSenderMail($newsletter->getSenderMail());
        $this->setReturnMail($newsletter->getReturnMail());

        $this->setUsername($sendSettings->getUsername());
        $this->setPassword($sendSettings->getPassword());
        $this->setHost($sendSettings->getHost());
        $this->setPort($sendSettings->getPort());
        $this->setEncryption($sendSettings->getEncryption());
        $this->setAuthMode($sendSettings->getAuthMode());
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

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword()
    {
            if (is_resource($this->password)) {
                $handle = $this->password;
                $content = '';
                while (!feof($handle)) {
                    $content .= fread($handle, 8192);
                }
                $this->password = $content;

                return $content;
            }

        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    public function getEncryption()
    {
        return $this->encryption;
    }

    public function setEncryption($encryption)
    {
        $this->encryption = $encryption;

        return $this;
    }

    public function getAuthMode()
    {
        return $this->authMode;
    }

    public function setAuthMode($authMode)
    {
        $this->authMode = $authMode;

        return $this;
    }
}
