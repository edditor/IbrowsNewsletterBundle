<?php

namespace Ibrows\Bundle\NewsletterBundle\Model\Mandant;

use Ibrows\Bundle\NewsletterBundle\Model\Newsletter\SendSettingsInterface;

use Doctrine\Common\Collections\ArrayCollection;

abstract class Mandant implements MandantInterface
{
    protected $id;

    protected $name;
    protected $hash;
    protected $salt;
    protected $editorName;
    protected $rendererName;

    protected $blocks;
    protected $designs;
    protected $newsletters;
    protected $subscribers;
    protected $subscriberGroups;

    protected $sendSettings;

    public function __construct()
    {
        $this->blocks = new ArrayCollection();
        $this->designs = new ArrayCollection();
        $this->newsletters = new ArrayCollection();
        $this->subscribers = new ArrayCollection();
        $this->subscriberGroups = new ArrayCollection();
        $this->salt = $this->generateSalt();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $name
     * @return Mandant
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getEditorName()
    {
        return $this->editorName;
    }

    public function setEditorName($editorName)
    {
        $this->editorName = $editorName;

        return $this;
    }

    public function getRendererName()
    {
        return $this->rendererName;
    }

    public function setRendererName($rendererName)
    {
        $this->rendererName = $rendererName;

        return $this;
    }

    public function getNewsletters()
    {
        return $this->newsletters;
    }

    public function getBlocks()
    {
        return $this->blocks;
    }

    public function getDesigns()
    {
        return $this->designs;
    }

    public function getSubscribers()
    {
        return $this->subscribers;
    }

    public function getSubscriberGroups()
    {
        return $this->subscriberGroups;
    }

    public function getSendSettings()
    {
        return $this->sendSettings;
    }

    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    public function setSendSettings(SendSettingsInterface $settings)
    {
        $this->sendSettings = $settings;

        return $this;
    }

    protected function generateSalt()
    {
        return sha1(uniqid().time());
    }
}
