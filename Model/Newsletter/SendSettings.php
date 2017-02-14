<?php

namespace Ibrows\Bundle\NewsletterBundle\Model\Newsletter;

use Symfony\Component\Validator\Constraints as Assert;

class SendSettings implements SendSettingsInterface
{
    /**
     * @Assert\NotBlank(groups={"newsletter"})
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @Assert\NotBlank(groups={"newsletter"})
     */
    protected $host;

    /**
     * @Assert\NotNull(groups={"newsletter"})
     * @Assert\Range(
     *        min = "1",
     *    max = "65535",
     *    groups={"newsletter"}
     *  )
     */
    protected $port;

    /**
     * @Assert\NotNull(groups={"newsletter"})
     * @Assert\Range(
     *        min = "1",
     *    max = "100",
     *    groups={"newsletter"}
     *  )
     */
    protected $interval;

    /**
     * @Assert\Choice(
     *        choices={"ssl","tls"},
     *        groups={"newsletter"}
     * )
     */
    protected $encryption;

    /**
     * @Assert\Choice(
     *        choices={"plain","login", "cram-md5"},
     *        groups={"newsletter"}
     * )
     */
    protected $authMode;

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

    public function getInterval()
    {
        return $this->interval;
    }

    public function setInterval($interval)
    {
        $this->interval = $interval;

        return $this;
    }

    public function getEncryption()
    {
        return $this->encryption;
    }

    public function setEncryption($encryption)
    {
        $this->encryption = $encryption;
    }

    public function getAuthMode()
    {
        return $this->authMode;
    }

    public function setAuthMode($authMode)
    {
        $this->authMode = $authMode;
    }
}
