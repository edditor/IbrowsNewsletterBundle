<?php

namespace Ibrows\Bundle\NewsletterBundle\Renderer\Bridge;

use Ibrows\Bundle\NewsletterBundle\Model\Mandant\MandantInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Newsletter\NewsletterInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Subscriber\SubscriberGenderTitleInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Subscriber\SubscriberInterface;
use Ibrows\Bundle\NewsletterBundle\Renderer\GenderTitleStrategy\GenderTitleStrategyInterface;
use Symfony\Component\Routing\RouterInterface;

class RendererBridge
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var GenderTitleStrategyInterface
     */
    protected $genderTitleStrategy;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $routeStatisticlogreadimage;

    /**
     * @var string
     */
    private $routeReadonlinelink;

    /**
     * @var string
     */
    private $routeUnsubscribe;

    /**
     * @param RouterInterface $router
     * @param GenderTitleStrategyInterface $genderTitleStrategy
     * @param string $host
     * @param string $routeStatisticlogreadimage
     * @param string $routeReadonlinelink
     * @param string $routeUnsubscribe
     */
    public function __construct(
        RouterInterface $router,
        GenderTitleStrategyInterface $genderTitleStrategy,
        $host,
        $routeStatisticlogreadimage,
        $routeReadonlinelink,
        $routeUnsubscribe
    ) {
        $this->router = $router;
        $this->genderTitleStrategy = $genderTitleStrategy;
        $this->host = $host;
        $this->routeStatisticlogreadimage = $routeStatisticlogreadimage;
        $this->routeReadonlinelink = $routeReadonlinelink;
        $this->routeUnsubscribe = $routeUnsubscribe;
    }

    /**
     * @param  string $format
     * @return string
     */
    public function now($format = 'd.m.Y')
    {
        $now = new \DateTime();

        return $now->format($format);
    }

    /**
     * @param  MandantInterface $mandant
     * @param  NewsletterInterface $newsletter
     * @param  SubscriberInterface $subscriber
     * @param  string $context
     * @return string
     */
    public function statisticlogreadimage(
        MandantInterface $mandant,
        NewsletterInterface $newsletter,
        SubscriberInterface $subscriber,
        $context
    ) {

        $src = $this->host.$this->router->generate(
            $this->routeStatisticlogreadimage,
            array(
                'mandantHash' => $mandant->getHash(),
                'newsletterHash' => $newsletter->getHash(),
                'subscriberHash' => $subscriber->getHash(),
                'context' => $context,
            ),
            RouterInterface::ABSOLUTE_PATH
        );

        return '<img width="0" height="0" src="'.$src.'" />';
    }

    /**
     * @param  MandantInterface $mandant
     * @param  NewsletterInterface $newsletter
     * @param  SubscriberInterface $subscriber
     * @param                      $context
     * @return string
     */
    public function readonlinelink(
        MandantInterface $mandant,
        NewsletterInterface $newsletter,
        SubscriberInterface $subscriber,
        $context
    ) {
        return $this->host.$this->router->generate(
            $this->routeReadonlinelink,
            array(
                'mandantHash' => $mandant->getHash(),
                'newsletterHash' => $newsletter->getHash(),
                'subscriberHash' => $subscriber->getHash(),
                'context' => $context,
            ),
            RouterInterface::ABSOLUTE_PATH
        );
    }

    /**
     * @param  MandantInterface $mandant
     * @param  NewsletterInterface $newsletter
     * @param  SubscriberInterface $subscriber
     * @param                      $context
     * @return string
     */
    public function unsubscribelink(
        MandantInterface $mandant,
        NewsletterInterface $newsletter,
        SubscriberInterface $subscriber,
        $context
    ) {
        return $this->host.$this->router->generate(
            $this->routeUnsubscribe,
            array(
                'mandantHash' => $mandant->getHash(),
                'newsletterHash' => $newsletter->getHash(),
                'subscriberHash' => $subscriber->getHash(),
                'context' => $context,
            ),
            RouterInterface::ABSOLUTE_PATH
        );
    }

    /**
     * @param  SubscriberGenderTitleInterface|SubscriberInterface $subscriber
     * @return string
     */
    public function gendertitle(SubscriberInterface $subscriber)
    {
        return $this->genderTitleStrategy->getGenderTitle($subscriber);
    }
}
