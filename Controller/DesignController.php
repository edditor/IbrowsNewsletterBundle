<?php
namespace Ibrows\Bundle\NewsletterBundle\Controller;

use Ibrows\Bundle\NewsletterBundle\Model\Design\Design;
use Ibrows\Bundle\NewsletterBundle\Model\Design\DesignInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/design")
 */
class DesignController extends AbstractController
{
    /**
     * @Route("/", name="ibrows_newsletter_design_index")
     */
    public function indexAction()
    {
        return $this->render($this->getTemplateManager()->getDesign('index'));
    }

    /**
     * @Route("/list", name="ibrows_newsletter_design_list")
     */
    public function listAction()
    {
        return $this->render(
            $this->getTemplateManager()->getDesign('list'),
            array(
                'designs' => $this->getMandant()->getDesigns(),
            )
        );
    }

    /**
     * @Route("/create", name="ibrows_newsletter_design_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        /** @var DesignInterface $design */
        $design = $this->getDesignManager()->create();

        $formtype = $this->getClassManager()->getForm('design');
        $form = $this->createForm(new $formtype(), $design);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->getMandantManager()->persistDesign($this->getMandantName(), $design);

                return $this->redirect($this->generateUrl('ibrows_newsletter_design_edit', array('id' => $design->getId())));
            }
        }

        return $this->render(
            $this->getTemplateManager()->getDesign('create'),
            array(
                'design' => $design,
                'form'   => $form->createView(),
            )
        );
    }

    /**
     * @Route("/edit/{id}", name="ibrows_newsletter_design_edit")
     * @param Request $request
     * @param int     $id
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        /** @var Design $design */
        $design = $this->getDesignManager()->get($id);

        $formtype = $this->getClassManager()->getForm('design');
        $form = $this->createForm(new $formtype(), $design);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->getMandantManager()->persistDesign($this->getMandantName(), $design);
            }
        }

        return $this->render(
            $this->getTemplateManager()->getDesign('edit'),
            array(
                'design' => $design,
                'form'   => $form->createView()
            )
        );
    }

    /**
     * @Route("/show/{id}", name="ibrows_newsletter_design_show")
     * @param string $id
     * @return Response
     */
    public function showAction($id)
    {
        return $this->render($this->getTemplateManager()->getDesign('show'));
    }
}
