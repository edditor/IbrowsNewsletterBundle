<?php

namespace Ibrows\Bundle\NewsletterBundle\Model\Mandant;

use Ibrows\Bundle\NewsletterBundle\Model\Block\BlockInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Design\DesignInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Newsletter\NewsletterInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Newsletter\SendSettingsInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Subscriber\GroupInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Subscriber\SubscriberInterface;

interface MandantInterface
{
    /**
     * @return NewsletterInterface[]
     */
    public function getNewsletters();

    /**
     * @return BlockInterface[]
     */
    public function getBlocks();

    /**
     * @return DesignInterface[]
     */
    public function getDesigns();

    /**
     * @return string
     */
    public function getEditorName();

    /**
     * @param string $editorName
     */
    public function setEditorName($editorName);

    /**
     * @return string
     */
    public function getRendererName();

    /**
     * @param string $rendererName
     */
    public function setRendererName($rendererName);

    /**
     * @return SendSettingsInterface
     */
    public function getSendSettings();

    /**
     * @param  SendSettingsInterface $settings
     */
    public function setSendSettings(SendSettingsInterface $settings);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param  string $name
     */
    public function setName($name);

    /**
     * @return SubscriberInterface[]
     */
    public function getSubscribers();

    /**
     * @return GroupInterface[]
     */
    public function getSubscriberGroups();

    /**
     * @return string
     */
    public function getHash();

    /**
     * @param  string $hash
     */
    public function setHash($hash);

    /**
     * @return string
     */
    public function getSalt();
}
