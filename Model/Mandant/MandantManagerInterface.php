<?php

namespace Ibrows\Bundle\NewsletterBundle\Model\Mandant;

use Ibrows\Bundle\NewsletterBundle\Model\Newsletter\NewsletterInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Design\DesignInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Subscriber\GroupInterface;

interface MandantManagerInterface
{
    const DEFAULT_NAME = 'default';

    /**
     * @param string $name
     */
    public function get($name);

    /**
     * @param  string                                                      $name
     * @return \Symfony\Component\Security\Core\User\UserProviderInterface
     */
    public function getUserProvider($name);

    /**
     * @param  string                                                                      $name
     * @return \Ibrows\Bundle\NewsletterBundle\Model\Newsletter\NewsletterManagerInterface
     */
    public function getNewsletterManager($name);

    /**
     * @param  string                                                              $name
     * @return \Ibrows\Bundle\NewsletterBundle\Model\Design\DesignManagerInterface
     */
    public function getDesignManager($name);

    /**
     * @param  string                                                                      $name
     * @return \Ibrows\Bundle\NewsletterBundle\Model\Subscriber\GroupManagerInterface
     */
    public function getGroupManager($name);

    /**
     * @param  string                                                                      $name
     * @return \Ibrows\Bundle\NewsletterBundle\Model\Subscriber\SubscriberManagerInterface
     */
    public function getSubscriberManager($name);

    /**
     * @param  string                                                                    $name
     * @return \Ibrows\Bundle\NewsletterBundle\Model\Statistic\StatisticManagerInterface
     */
    public function getStatisticManager($name);

    /**
     * @param  string                                     $name
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    public function getObjectManager($name);

    /**
     * @param  string                   $name
     * @param  NewsletterInterface      $newsletter
     * @return NewsletterInterface|null
     */
    public function persistNewsletter($name, NewsletterInterface $newsletter);

    /**
     * @param  string               $name
     * @param  DesignInterface      $design
     * @return DesignInterface|null
     */
    public function persistDesign($name, DesignInterface $design);
}
