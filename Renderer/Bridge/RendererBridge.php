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
    protected $router;
    protected $genderTitleStrategy;

    /**
     * @param RouterInterface              $router
     * @param GenderTitleStrategyInterface $genderTitleStrategy
     */
    public function __construct(
        RouterInterface $router,
        GenderTitleStrategyInterface $genderTitleStrategy
    ) {
        $this->router = $router;
        $this->genderTitleStrategy = $genderTitleStrategy;
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
     * @param  MandantInterface    $mandant
     * @param  NewsletterInterface $newsletter
     * @param  SubscriberInterface $subscriber
     * @param  string              $context
     * @return string
     */
    public function statisticlogreadimage(MandantInterface $mandant, NewsletterInterface $newsletter, SubscriberInterface $subscriber, $context)
    {
        $src = $this->router->generate(
            'ibrows_newsletter_statistic_log_read',
            array(
                'mandantHash'    => $mandant->getHash(),
                'newsletterHash' => $newsletter->getHash(),
                'subscriberHash' => $subscriber->getHash(),
                'context'        => $context
            ),
            true
        );

        return '<img width="0" height="0" src="' . $src . '" />';
    }

    /**
     * @param  MandantInterface    $mandant
     * @param  NewsletterInterface $newsletter
     * @param  SubscriberInterface $subscriber
     * @param                      $context
     * @return string
     */
    public function readonlinelink(MandantInterface $mandant, NewsletterInterface $newsletter, SubscriberInterface $subscriber, $context)
    {
        return $this->router->generate(
            'ibrows_newsletter_render_overview',
            array(
                'mandantHash'    => $mandant->getHash(),
                'newsletterHash' => $newsletter->getHash(),
                'subscriberHash' => $subscriber->getHash(),
                'context'        => $context
            ),
            true
        );
    }

    /**
     * @param  MandantInterface    $mandant
     * @param  NewsletterInterface $newsletter
     * @param  SubscriberInterface $subscriber
     * @param                      $context
     * @return string
     */
    public function unsubscribelink(MandantInterface $mandant, NewsletterInterface $newsletter, SubscriberInterface $subscriber, $context)
    {
        return $this->router->generate(
            'ibrows_newsletter_unsubscribe',
            array(
                'mandantHash'    => $mandant->getHash(),
                'newsletterHash' => $newsletter->getHash(),
                'subscriberHash' => $subscriber->getHash(),
                'context'        => $context
            ),
            true
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
