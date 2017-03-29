<?php

namespace Ibrows\Bundle\NewsletterBundle\Model\Newsletter;

use Doctrine\Common\Collections\Collection;
use Ibrows\Bundle\NewsletterBundle\Model\Block\BlockInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Design\DesignInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Mandant\MandantInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Subscriber\SubscriberInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Subscriber\GroupInterface;

interface NewsletterInterface
{
    const STATUS_ONHOLD = 'onhold';
    const STATUS_READY = 'ready';
    const STATUS_WORKING = 'working';
    const STATUS_COMPLETED = 'completed';
    const STATUS_ERROR = 'error';
    const STATUS_CANCELED = 'canceled';

    /**
     * @return string
     */
    public function getName();

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @return MandantInterface
     */
    public function getMandant();

    /**
     * @return string
     */
    public function getSubject();

    /**
     * @return string
     */
    public function getSenderMail();

    /**
     * @return string
     */
    public function getSenderName();

    /**
     * @return string
     */
    public function getReturnMail();

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getHash();

    /**
     * @return SubscriberInterface[]|Collection
     */
    public function getSubscribers();

    /**
     * @return GroupInterface[]|Collection
     */
    public function getGroups();

    /**
     * @return SendSettingsInterface
     */
    public function getSendSettings();

    /**
     * @param  SendSettingsInterface $settings
     * @return NewsletterInterface
     */
    public function setSendSettings(SendSettingsInterface $settings);

    /**
     * @param MandantInterface $mandant
     * @return NewsletterInterface
     */
    public function setMandant(MandantInterface $mandant);

    /**
     * @return BlockInterface[]|Collection
     */
    public function getBlocks();

    /**
     * @param  BlockInterface $block
     * @return NewsletterInterface
     */
    public function addBlock(BlockInterface $block);

    /**
     * @param  BlockInterface $block
     * @return NewsletterInterface
     */
    public function removeBlock(BlockInterface $block);

    /**
     * @return DesignInterface
     */
    public function getDesign();

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @param string $status
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getError();

    /**
     * @param string $error
     */
    public function setError($error);

    /**
     * @return \DateTime
     */
    public function getStarttime();

    /**
     * @param \DateTime $starttime
     */
    public function setStarttime($starttime);
}
