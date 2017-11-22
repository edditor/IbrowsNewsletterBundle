<?php
namespace Ibrows\Bundle\NewsletterBundle\Entity;

use Ibrows\Bundle\NewsletterBundle\Model\Log\Log as BaseLog;
use Doctrine\ORM\Mapping as ORM;

class Log extends BaseLog
{

    /**
     * @ORM\Column(type="integer", name="newsletter_id", nullable=true)
     */
    protected $newsletterId;

    /**
     * @ORM\Column(type="integer", name="subscriber_id", nullable=true)
     */
    protected $subscriberId;

    /**
     * @ORM\Column(type="string", name="subscriber_firstname", nullable=true)
     */
    protected $subscriberFirstname;

    /**
     * @ORM\Column(type="string", name="subscriber_lastname", nullable=true)
     */
    protected $subscriberLastname;

    /**
     * @ORM\Column(type="string", name="subscriber_gender", nullable=true)
     */
    protected $subscriberGender;

    /**
     * @ORM\Column(type="string", name="subscriber_title", nullable=true)
     */
    protected $subscriberTitle;

    /**
     * @ORM\Column(type="string", name="subscriber_locale", nullable=true)
     */
    protected $subscriberLocale;

    /**
     * @ORM\Column(type="string", name="subscriber_email", nullable=true)
     */
    protected $subscriberEmail;

    /**
     * @ORM\Column(type="string", name="subscriber_companyname", nullable=true)
     */
    protected $subscriberCompanyname;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $message;

    /**
     * @ORM\Column(type="string", name="mandant_name", nullable=true)
     */
    protected $mandantName;

    /**
     * @ORM\Column(type="text", name="subscriber_useragent", nullable=true)
     */
    protected $subscriberUseragent;

}
