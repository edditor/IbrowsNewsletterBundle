<?php

namespace Ibrows\Bundle\NewsletterBundle\Model\Newsletter;

interface SendSettingsInterface
{
    public function getUsername();

    public function setUsername($username);

    public function getPassword();

    public function setPassword($password);

    public function getHost();

    public function setHost($host);

    public function getPort();

    public function setPort($port);

    public function getInterval();

    public function setInterval($interval);

    /**
     * @return string
     */
    public function getEncryption();

    /**
     * @param string $encryption
     */
    public function setEncryption($encryption);

    /**
     * @return string
     */
    public function getAuthMode();

    /**
     * @param string $authMode
     */
    public function setAuthMode($authMode);
}
