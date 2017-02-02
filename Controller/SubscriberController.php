<?php

namespace Ibrows\Bundle\NewsletterBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/subscriber")
 */
class SubscriberController extends AbstractController
{
    /**
     * @Route("/list", name="ibrows_newsletter_subscriber_list")
     */
    public function listAction()
    {
        $subscribers = $this->getSubscribers();

        return $this->render($this->getTemplateManager()->getSubscriber('list'), array(
            'subscribers' => $subscribers
        ));
    }

    /**
     * @Route("/show/{id}", name="ibrows_newsletter_subscriber_show")
     * @param string $id
     * @return Response
     */
    public function showAction($id)
    {
        $subscriber = $this->getSubscriberManager()->get($id);
        return $this->render($this->getTemplateManager()->getSubscriber('show'),
            array(
                'subscriber' => $subscriber,
            )
        );
    }
}
