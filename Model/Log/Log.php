<?php

namespace Ibrows\Bundle\NewsletterBundle\Model\Log;

abstract class Log implements LogInterface
{
    protected $id;

    protected $newsletterId;
    protected $subscriberId;

    protected $subscriberGender;
    protected $subscriberTitle;
    protected $subscriberCompanyname;
    protected $subscriberFirstname;
    protected $subscriberLastname;
    protected $subscriberLocale;
    protected $subscriberEmail;

    protected $createdAt;
    protected $message;
    protected $mandantName;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return integer
     */
    public function getNewsletterId()
    {
        return $this->newsletterId;
    }

    /**
     * @param  integer $newsletterId
     * @return LogInterface
     */
    public function setNewsletterId($newsletterId)
    {
        $this->newsletterId = $newsletterId;

        return $this;
    }

    /**
     * @return integer
     */
    public function getSubscriberId()
    {
        return $this->subscriberId;
    }

    /**
     * @param  integer $subscriberId
     * @return LogInterface
     */
    public function setSubscriberId($subscriberId)
    {
        $this->subscriberId = $subscriberId;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param  string $message
     * @return LogInterface
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubscriberLocale()
    {
        return $this->subscriberLocale;
    }

    /**
     * @param  string $locale
     * @return LogInterface
     */
    public function setSubscriberLocale($locale)
    {
        $this->subscriberLocale = $locale;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubscriberEmail()
    {
        return $this->subscriberEmail;
    }

    /**
     * @param  string $email
     * @return LogInterface
     */
    public function setSubscriberEmail($email)
    {
        $this->subscriberEmail = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubscriberGender()
    {
        return $this->subscriberGender;
    }

    /**
     * @param  string $gender
     * @return LogInterface
     */
    public function setSubscriberGender($gender)
    {
        $this->subscriberGender = $gender;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubscriberTitle()
    {
        return $this->subscriberTitle;
    }

    /**
     * @param  string $title
     * @return LogInterface
     */
    public function setSubscriberTitle($title)
    {
        $this->subscriberTitle = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubscriberFirstname()
    {
        return $this->subscriberFirstname;
    }

    /**
     * @param  string $firstname
     * @return LogInterface
     */
    public function setSubscriberFirstname($firstname)
    {
        $this->subscriberFirstname = $firstname;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubscriberLastname()
    {
        return $this->subscriberLastname;
    }

    /**
     * @param  string $lastname
     * @return LogInterface
     */
    public function setSubscriberLastname($lastname)
    {
        $this->subscriberLastname = $lastname;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubscriberCompanyname()
    {
        return $this->subscriberCompanyname;
    }

    /**
     * @param string $companyname
     * @return $this
     */
    public function setSubscriberCompanyname($companyname)
    {
        $this->subscriberCompanyname = $companyname;

        return $this;
    }

    /**
     *
     * @param  string $name
     * @return LogInterface
     */
    public function setMandantName($name)
    {
        $this->mandantName = $name;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getMandantName()
    {
        return $this->mandantName;
    }
}
