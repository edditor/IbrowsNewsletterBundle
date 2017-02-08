<?php

namespace Ibrows\Bundle\NewsletterBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/create", name="ibrows_newsletter_subscriber_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        /** @var SubscriberInterface $subscriber */
        $subscriber = $this->getSubscriberManager()->create();

        $formtype = $this->getClassManager()->getForm('subscriber');
        $form = $this->createForm(new $formtype($this->getMandantName(), $this->getClassManager(), $this->getMandant()), $subscriber);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->getMandantManager()->persistSubscriber($this->getMandantName(), $subscriber);
                $request->getSession()->getFlashBag()->add('success',
                    $this->get('translator')->trans('subscriber.create.success', [], 'IbrowsNewsletterBundle'));
                return $this->redirect($this->generateUrl('ibrows_newsletter_subscriber_list'));
            }
        }

        return $this->render(
            $this->getTemplateManager()->getSubscriber('create'),
            array(
                'subscriber' => $subscriber,
                'form'   => $form->createView(),
            )
        );
    }

    /**
     * @Route("/edit/{id}", name="ibrows_newsletter_subscriber_edit")
     * @param Request $request
     * @param int     $id
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        /** @var Subscriber $subscriber */
        $subscriber = $this->getSubscriberManager()->get($id);

        $formtype = $this->getClassManager()->getForm('subscriber');
        $form = $this->createForm(new $formtype($this->getMandantName(), $this->getClassManager(), $this->getMandant()), $subscriber);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->getMandantManager()->persistSubscriber($this->getMandantName(), $subscriber);
                $request->getSession()->getFlashBag()->add('success',
                    $this->get('translator')->trans('subscriber.edit.success', [], 'IbrowsNewsletterBundle'));
            }
        }

        return $this->render(
            $this->getTemplateManager()->getSubscriber('edit'),
            array(
                'subscriber' => $subscriber,
                'form'   => $form->createView(),
            )
        );
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
