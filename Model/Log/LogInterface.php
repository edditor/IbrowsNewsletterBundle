<?php
namespace Ibrows\Bundle\NewsletterBundle\Model\Log;

interface LogInterface
{

    /**
     * @return integer
     */
    public function getId();

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @return integer
     */
    public function getNewsletterId();

    /**
     * @param  integer      $newsletterId
     * @return LogInterface
     */
    public function setNewsletterId($newsletterId);

    /**
     * @return integer
     */
    public function getSubscriberId();

    /**
     * @param  integer      $subscriberId
     * @return LogInterface
     */
    public function setSubscriberId($subscriberId);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param  string       $message
     * @return LogInterface
     */
    public function setMessage($message);

    /**
     * @return string
     */
    public function getSubscriberLocale();

    /**
     * @param  string       $locale
     * @return LogInterface
     */
    public function setSubscriberLocale($locale);

    /**
     * @return string
     */
    public function getSubscriberEmail();

    /**
     * @param  string       $email
     * @return LogInterface
     */
    public function setSubscriberEmail($email);

    /**
     * @return string
     */
    public function getSubscriberGender();

    /**
     * @param  string       $gender
     * @return LogInterface
     */
    public function setSubscriberGender($gender);

    /**
     * @return string
     */
    public function getSubscriberTitle();

    /**
     * @param  string       $title
     * @return LogInterface
     */
    public function setSubscriberTitle($title);

    /**
     * @return string
     */
    public function getSubscriberFirstname();

    /**
     * @param  string       $firstname
     * @return LogInterface
     */
    public function setSubscriberFirstname($firstname);

    /**
     * @return string
     */
    public function getSubscriberLastname();

    /**
     * @param  string       $lastname
     * @return LogInterface
     */
    public function setSubscriberLastname($lastname);

    /**
     * @return string
     */
    public function getSubscriberCompanyname();

    /**
     * @param  string       $companyname
     * @return LogInterface
     */
    public function setSubscriberCompanyname($companyname);

    /**
     * @return string
     */
    public function getMandantName();

    /**
     * @param  string       $mandantName
     * @return LogInterface
     */
    public function setMandantName($mandantName);

    /**
     * @return string
     */
    public function getSubscriberUseragent();

    /**
     * @param  string       $useragent
     * @return LogInterface
     */
    public function setSubscriberUseragent($useragent);
}
