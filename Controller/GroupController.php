<?php

namespace Ibrows\Bundle\NewsletterBundle\Controller;

use Ibrows\Bundle\NewsletterBundle\Model\Subscriber\Group;
use Ibrows\Bundle\NewsletterBundle\Model\Subscriber\GroupInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/group")
 */
class GroupController extends AbstractController
{
    /**
     * @Route("/list", name="ibrows_newsletter_group_list")
     */
    public function listAction()
    {
        $groups = $this->getSubscriberGroups();

        return $this->render($this->getTemplateManager()->getGroup('list'), array(
            'groups' => $groups
        ));
    }


    /**
     * @Route("/create", name="ibrows_newsletter_group_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        /** @var GroupInterface $group */
        $group = $this->getGroupManager()->create();

        $formtype = $this->getClassManager()->getForm('group');
        $form = $this->createForm(new $formtype($this->getMandantName(), $this->getClassManager(), $this->getMandant()), $group);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->getMandantManager()->persistGroup($this->getMandantName(), $group);
                $request->getSession()->getFlashBag()->add('success',
                    $this->get('translator')->trans('group.create.success', [], 'IbrowsNewsletterBundle'));
                return $this->redirect($this->generateUrl('ibrows_newsletter_group_list'));
            }
        }

        return $this->render(
            $this->getTemplateManager()->getGroup('create'),
            array(
                'group' => $group,
                'form'   => $form->createView(),
            )
        );
    }

    /**
     * @Route("/edit/{id}", name="ibrows_newsletter_group_edit")
     * @param Request $request
     * @param int     $id
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        /** @var Group $group */
        $group = $this->getGroupManager()->get($id);

        $formtype = $this->getClassManager()->getForm('group');
        $form = $this->createForm(new $formtype($this->getMandantName(), $this->getClassManager(), $this->getMandant()), $group);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->getMandantManager()->persistGroup($this->getMandantName(), $group);
                $request->getSession()->getFlashBag()->add('success',
                    $this->get('translator')->trans('group.edit.success', [], 'IbrowsNewsletterBundle'));
            }
        }

        $subscribers = $this->getSubscribers();

        return $this->render(
            $this->getTemplateManager()->getGroup('edit'),
            array(
                'group' => $group,
                'form'   => $form->createView(),
                'subscribers' => $subscribers,
            )
        );
    }

    /**
     * @Route("/show/{id}", name="ibrows_newsletter_group_show")
     * @param string $id
     * @return Response
     */
    public function showAction($id)
    {
        $group = $this->getGroupManager()->get($id);
        return $this->render($this->getTemplateManager()->getGroup('show'),
            array(
                'group' => $group,
            )
        );
    }

    /**
     * @Route("/delete/{id}", name="ibrows_newsletter_group_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        /** @var Group $group */
        $group = $this->getGroupManager()->get($id);
        $this->getMandantManager()->deleteGroup($this->getMandantName(), $group);
        $request->getSession()->getFlashBag()->add('success',
            $this->get('translator')->trans('group.delete.success', array('%id%' => $id), 'IbrowsNewsletterBundle'));

        return $this->redirect($this->generateUrl('ibrows_newsletter_group_list'));
    }

}
