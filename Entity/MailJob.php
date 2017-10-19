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
     * @ORM\Column(type="text", nullable=true)
     */
    protected $attachments;

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
     * @ORM\Column(type="string", nullable=true)
     */
    protected $salt;

    /**
     * @var SendSettingsInterface
     * @ORM\ManyToOne(targetEntity="SendSettings")
     * @ORM\JoinColumn(name="send_settings_id", referencedColumnName="id")
     */
    protected $sendSettings;

    /**
     * @var Newsletter
     * @ORM\ManyToOne(targetEntity="Newsletter")
     * @ORM\JoinColumn(name="newsletter_id", referencedColumnName="id")
     */
    protected $newsletter;

    public function getId()
    {
        return $this->id;
    }
}
