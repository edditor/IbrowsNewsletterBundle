<?php

namespace Ibrows\Bundle\NewsletterBundle\Entity;

use Ibrows\Bundle\NewsletterBundle\Model\Job\MailJob as BaseMailJob;

use Doctrine\ORM\Mapping as ORM;

class MailJob extends BaseMailJob
{
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $subject;

    /**
     * @ORM\Column(type="string", name ="sender_name", nullable=true)
     */
    protected $senderName;

    /**
     * @ORM\Column(type="string", name ="sender_mail")
     */
    protected $senderMail;

    /**
     * @ORM\Column(type="string", name ="return_mail")
     */
    protected $returnMail;

    /**
     * @ORM\Column(type="string", name ="to_mail")
     */
    protected $toMail;

    /**
     * @ORM\Column(type="string", name ="to_name")
     */
    protected $toName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $body;

    /**
     * @ORM\Column(type="integer", name="newsletter_id")
     */
    protected $newsletterId;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $error;

    /**
     * @ORM\Column(type="string")
     */
    protected $status;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $scheduled;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $completed;

    /**
     * @ORM\Column(type="string")
     */
    protected $username;

    /**
     * @ORM\Column(type="blob")
     */
    protected $password;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $host;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $port;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $salt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $encryption;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $authMode;

    public function getId()
    {
        return $this->id;
    }
}
