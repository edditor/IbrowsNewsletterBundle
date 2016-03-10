<?php

namespace Ibrows\Bundle\NewsletterBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/unsubscribe")
 */
class UnsubscribeController extends AbstractHashMandantController
{
    /**
     * @Route("/do/{mandantHash}/{newsletterHash}/{subscriberHash}", name="ibrows_newsletter_unsubscribe")
     * @param Request $request
     * @param string  $mandantHash
     * @param string  $newsletterHash
     * @param string  $subscriberHash
     * @return Response
     */
    public function unsubscribeAction(Request $request, $mandantHash, $newsletterHash, $subscriberHash)
    {
        $this->setMandantNameByHash($mandantHash);

        $newsletter = $this->getNewsletterByHash($newsletterHash);
        $subscriber = $this->getSubscriberByHash($newsletter, $subscriberHash);

        if (!$request->query->get('context')) {
            $this->addNewsletterReadLog($newsletter, $subscriber, "Newsletter read: logged by " . __METHOD__);
        }

        $groupClass = $this->getClassManager()->getModel('group');

        $formtype = $this->getClassManager()->getForm('unsubscribe');
        $form = $this->createForm(new $formtype($this->getMandantName(), $groupClass), $subscriber);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->setNewsletter($newsletter);
            }
        }

        return $this->render(
            $this->getTemplateManager()->getNewsletter('unsubscribe'),
            array(
                'form'       => $form->createView(),
                'subscriber' => $subscriber,
                'newsletter' => $newsletter
            )
        );
    }
}
