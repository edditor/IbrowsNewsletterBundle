<?php

namespace Ibrows\Bundle\NewsletterBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
}
