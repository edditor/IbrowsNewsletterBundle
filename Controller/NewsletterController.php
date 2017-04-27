<?php

namespace Ibrows\Bundle\NewsletterBundle\Controller;

use Doctrine\Common\Collections\Collection;
use Ibrows\Bundle\NewsletterBundle\Annotation\Wizard\Annotation as WizardAction;
use Ibrows\Bundle\NewsletterBundle\Annotation\Wizard\AnnotationHandler as WizardActionHandler;
use Ibrows\Bundle\NewsletterBundle\Model\Block\BlockInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Job\MailJob;
use Ibrows\Bundle\NewsletterBundle\Model\Newsletter\NewsletterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NewsletterController extends AbstractController
{
    /**
     * @Route("/cancel/{id}", name="ibrows_newsletter_cancel")
     * @param int $id
     * @return RedirectResponse
     */
    public function cancelAction(Request $request, $id)
    {
        $newsletter = $this->getNewsletterById($id);

        //TODO later use STATUS_CANCELED
        $newsletter->setStatus(NewsletterInterface::STATUS_ONHOLD);

        $this->getMandantManager()->persistNewsletter($this->getMandantName(), $newsletter);
        $request->getSession()->getFlashBag()->add('success',
            $this->get('translator')->trans('newsletter.cancel.success', [], 'IbrowsNewsletterBundle'));

        return $this->redirect($this->generateUrl('ibrows_newsletter_list'));
    }

    /**
     * @Route("/", name="ibrows_newsletter_index")
     */
    public function indexAction()
    {
        $this->setNewsletter(null);

        return $this->render(
            $this->getTemplateManager()->getNewsletter('index'),
            array()
        );
    }

    /**
     * @Route("/list", name="ibrows_newsletter_list")
     */
    public function listAction()
    {
        $this->setNewsletter(null);

        return $this->render(
            $this->getTemplateManager()->getNewsletter('list'),
            array(
                'newsletters' => $this->getMandant()->getNewsletters()
            )
        );
    }

    /**
     * @Route("/edit/redirection/{id}", name="ibrows_newsletter_edit_redirection")
     * @param int $id
     * @return RedirectResponse
     */
    public function editredirectionAction($id)
    {
        $newsletter = $this->getNewsletterById($id);
        $this->setNewsletter($newsletter);

        return $this->redirect($this->generateUrl('ibrows_newsletter_meta'));
    }

    /**
     * @Route("/create", name="ibrows_newsletter_create")
     */
    public function createrediractionAction()
    {
        $this->setNewsletter(null);

        return $this->redirect($this->generateUrl('ibrows_newsletter_edit'));
    }

    /**
     * @Route("/meta", name="ibrows_newsletter_meta")
     * @WizardAction(name="meta", number=1, validationMethod="metaValidation")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function metaAction(Request $request)
    {
        $newsletter = $this->getNewsletter();
        if ($newsletter === null) {
            $newsletter = $this->getNewsletterManager()->create();
        }
        if ($newsletter->getStarttime() === null) {
            $newsletter->setStarttime(new \DateTime());
        }

        $formtype = $this->getClassManager()->getForm('newsletter');
        $form = $this->createForm(new $formtype($this->getMandantName(), $this->getClassManager()), $newsletter);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                if ($newsletter->getStatus() === null) {
                    $newsletter->setStatus($newsletter::STATUS_ONHOLD);
                }
                $this->setNewsletter($newsletter);

                return $this->redirect($this->getWizardActionAnnotationHandler()->getNextStepUrl());
            }
        }

        return $this->render(
            $this->getTemplateManager()->getNewsletter('create'),
            array(
                'newsletter' => $newsletter,
                'form'       => $form->createView(),
                'wizard'     => $this->getWizardActionAnnotationHandler(),
            )
        );
    }

    /**
     * @param WizardActionHandler $handler
     */
    public function metaValidation(WizardActionHandler $handler)
    {
    }

    /**
     * @Route("/edit", name="ibrows_newsletter_edit")
     * @WizardAction(name="edit", number=2, validationMethod="editValidation")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request)
    {
        if (($response = $this->getWizardActionValidation()) instanceof Response) {
            return $response;
        }

        $newsletter = $this->getNewsletter();

        $editorName = $this->getMandant()->getEditorName();
        $editorManager = $this->getEditormanager();

        $editor = $editorManager->get($editorName);

        $design = $newsletter->getDesign();
        if ($newsletter->getDesign()->getName() !== 'default') {
            $editor->setTemplate($design);
        }

        if ($request->getMethod() == 'POST') {
            $content = $request->request->get('content');
            //update content
            if ($content) {
                $content = $editor->preSaveContentFromEditor($content);
                $name = 'design_'.$newsletter->getHash();
                /** @var DesignInterface $design */
                $design = $this->getDesignManager()->findOneBy(array('name' => $name));
                if (!$design) {
                    $design = $this->getDesignManager()->create();
                    $design->setName($name);
                    $newsletter->setDesign($design);
                }
                $design->setContent($content);
                $this->getMandantManager()->persistDesign($this->getMandantName(), $design);
            }

            if ($request->request->get('continue')) {
                return new RedirectResponse($this->getWizardActionAnnotationHandler()->getNextStepUrl());
            }
        }

        return $this->render(
            $this->getTemplateManager()->getNewsletter('edit'),
            array(
                'newsletter'           => $newsletter,
                'wizard'               => $this->getWizardActionAnnotationHandler(),
                'head_javascripts' => $editor->renderHeadJavascripts(),
                'head_styles' => $editor->renderHeadStyles(),
                'newsletter_content' => $editor->renderContent(),
            )
        );
    }

    /**
     * @param WizardActionHandler $handler
     * @return RedirectResponse|null
     */
    public function editValidation(WizardActionHandler $handler)
    {
        $newsletter = $this->getNewsletter();

        if (is_null($newsletter)) {
            return $this->redirect($handler->getStepUrl($handler->getLastValidAnnotation()));
        }

        return null;
    }

    /**
     * @Route("/group", name="ibrows_newsletter_group")
     * @WizardAction(name="groups", number=3, validationMethod="groupValidation")
     * @param Request $request
     * @return RedirectResponse|Response|true
     */
    public function groupAction(Request $request)
    {

        if (($response = $this->getWizardActionValidation()) instanceof Response) {
            return $response;
        }

        $newsletter = $this->getNewsletter();

        $formtype = $this->getClassManager()->getForm('groups');
        $form = $this->createForm(new $formtype($this->getMandantName(), $this->getClassManager(), $this->getMandant()), $newsletter);

        if ($request->isMethod('POST')) {
            $groupFormData = $request->request->get($form->getName());

            if ($form->has('groups')) {
                $groupsFormName = $form->get('groups')->getName();
                if (
                    isset($groupFormData[$groupsFormName]) &&
                    is_string($groupFormData[$groupsFormName])
                ) {
                    $groupFormData[$groupsFormName] = json_decode($groupFormData[$groupsFormName]);
                    $request->request->set($form->getName(), $groupFormData);
                }
            }

            $form->handleRequest($request);

            if ($form->isValid()) {
                //setSubscribers by selected Groups
                $newsletter->resetSubscribers();
                foreach ($newsletter->getGroups() as $group) {
                    $newsletter->addSubscribers($group->getSubscribers());
                }

                $this->setNewsletter($newsletter);
                return $this->redirect($this->getWizardActionAnnotationHandler()->getNextStepUrl());
            }
        }

        return $this->render(
            $this->getTemplateManager()->getNewsletter('group'),
            array(
                'newsletter' => $this->getNewsletter(),
                'form'       => $form->createView(),
                'wizard'     => $this->getWizardActionAnnotationHandler(),
            )
        );
    }

    /**
     * @param WizardActionHandler $handler
     * @return RedirectResponse|null
     */
    public function groupValidation(WizardActionHandler $handler)
    {
        $newsletter = $this->getNewsletter();

        if (is_null($newsletter)) {
            return $this->redirect($handler->getStepUrl($handler->getLastValidAnnotation()));
        }

        //TODO
        /*if (is_null($newsletter->getDesign())) {
            return $this->redirect($this->generateUrl('ibrows_newsletter_edit'));
        }*/

        return null;
    }

    /**
     * @Route("/summary", name="ibrows_newsletter_summary")
     * @WizardAction(name="summary", number=4, validationMethod="summaryValidation")
     * @param Request $request
     * @return Response
     */
    public function summaryAction(Request $request)
    {
        if (($response = $this->getWizardActionValidation()) instanceof Response) {
            return $response;
        }

        $newsletter = $this->getNewsletter();

        $subscribersArray = array();
        foreach ($newsletter->getSubscribers() as $subscriber) {
            $subscribersArray[$subscriber->getId()] = $subscriber;
        }

        $formtypeClassName = $this->getClassManager()->getForm('testmail');
        $formtype = new $formtypeClassName(
            $subscribersArray,
            $this->getUser()->getEmail()
        );

        $testmailform = $this->createForm($formtype);

        $error = '';
        if ($request->getMethod() == 'POST' && $request->request->get('testmail')) {
            $testmailform->handleRequest($request);

            if ($testmailform->isValid()) {
                $mandant = $this->getMandant();
                $subscriberId = $testmailform->get('subscriber')->getData();
                $subscriber = $this->getSubscriberById($newsletter, $subscriberId);
                $bridge = $this->getRendererBridge();

                $overview = $this->getRendererManager()->renderNewsletter(
                    $mandant->getRendererName(),
                    $bridge,
                    $newsletter,
                    $mandant,
                    $subscriber,
                    'testmail'
                );

                $mailjobClass = $this->getClassManager()->getModel('mailjob');
                $tomail = $testmailform->get('email')->getData();

                /** @var MailJob $mailjob */
                $mailjob = new $mailjobClass($newsletter, $this->getSendSettings());
                $mailjob->setToMail($tomail);
                $mailjob->setBody($overview);

                try {
                    $this->send($mailjob);
                } catch (\Swift_SwiftException $e) {
                    $message = $e->getMessage();
                    if ($message) {
                        $this->get('session')->getFlashBag()->add('ibrows_newsletter_error', 'newsletter.error.mail');
                        $error = $e;
                    }
                }
            }
        }

        return $this->render(
            $this->getTemplateManager()->getNewsletter('summary'),
            array(
                'newsletter'   => $newsletter,
                'subscriber'   => $newsletter->getSubscribers()->first(),
                'mandantHash'  => $this->getMandant()->getHash(),
                'testmailform' => $testmailform->createView(),
                'wizard'       => $this->getWizardActionAnnotationHandler(),
                'error'        => $error,
            )
        );
    }

    /**
     * @param WizardActionHandler $handler
     * @return null|RedirectResponse
     */
    public function summaryValidation(WizardActionHandler $handler)
    {
        $newsletter = $this->getNewsletter();
        $sendSettings = $this->getSendSettings();

        if (is_null($newsletter) || is_null($sendSettings)) {
            return $this->redirect($handler->getStepUrl($handler->getLastValidAnnotation()));
        }

        //TODO
        /*if (is_null($newsletter->getDesign())) {
            return $this->redirect($this->generateUrl('ibrows_newsletter_edit'));
        }*/

        if (count($newsletter->getGroups()) <= 0) {
            return $this->redirect($this->generateUrl('ibrows_newsletter_group'));
        }

        if (count($newsletter->getSubscribers()) === 0) {
            return $this->redirect($this->generateUrl('ibrows_newsletter_group'));
        }

        return null;
    }

    /**
     * @Route("/generate/mailjobs", name="ibrows_newsletter_generate_mail_jobs")
     */
    public function generateMailJobsAction()
    {
        $newsletter = $this->getNewsletter();
        $objectManager = $this->getObjectManager();
        $newsletter->setStatus($newsletter::STATUS_READY);
        $objectManager->flush();

        return $this->redirect($this->generateUrl('ibrows_newsletter_statistic_show', array('newsletterId' => $newsletter->getId())));
    }

    /**
     * @Route("/send", name="ibrows_newsletter_send")
     */
    public function sendAction()
    {
        $newsletter = $this->getNewsletter();
        if (is_null($newsletter)) {
            return $this->redirect($this->generateUrl('ibrows_newsletter_index', array(), true));
        }

        return $this->render(
            $this->getTemplateManager()->getNewsletter('send'),
            array(
                'newsletter' => $newsletter
            )
        );
    }

    /**
     * @param MailJob $job
     */
    protected function send(MailJob $job)
    {
        $this->get('ibrows_newsletter.mailer')->send($job);
    }

    /**
     * @param Collection|BlockInterface[] $blocks
     * @param array $blockParameters
     */
    protected function updateBlocksRecursive(Collection $blocks, array $blockParameters)
    {
        foreach ($blocks as $block) {
            $parameters = isset($blockParameters[$block->getId()]) ?
                $blockParameters[$block->getId()] : null;

            $provider = $this->getBlockProviderManager()->get($block->getProviderName());
            $provider->updateBlock($block, $parameters);

            $this->updateBlocksRecursive($block->getBlocks(), $blockParameters);
        }
    }

    /**
     * @param NewsletterInterface $newsletter
     * @param NewsletterInterface $cloneNewsletter
     */
    protected function cloneNewsletterBlocks(NewsletterInterface $newsletter, NewsletterInterface $cloneNewsletter)
    {
        foreach ($cloneNewsletter->getBlocks() as $parentBlock) {
            $cloneParentBlock = clone $parentBlock;
            $cloneParentBlock->setBlocks(array());
            $newsletter->addBlock($cloneParentBlock);

            $provider = $this->getBlockProviderManager()->get($cloneParentBlock->getProviderName());
            $provider->updateClonedBlock($cloneParentBlock);

            $this->loopCloneNewsletterBlocks($parentBlock, $cloneParentBlock);
        }
    }

    /**
     * @param BlockInterface $parentBlock
     * @param BlockInterface $cloneParentBlock
     */
    protected function loopCloneNewsletterBlocks(BlockInterface $parentBlock, BlockInterface $cloneParentBlock)
    {
        foreach ($parentBlock->getBlocks() as $childBlock) {
            $cloneChildBlock = clone $childBlock;
            $cloneChildBlock->setBlocks(array());
            $cloneParentBlock->addBlock($cloneChildBlock);

            $provider = $this->getBlockProviderManager()->get($cloneChildBlock->getProviderName());
            $provider->updateClonedBlock($cloneChildBlock);

            if ($childBlock->isCompound()) {
                $this->loopCloneNewsletterBlocks($childBlock, $cloneChildBlock);
            }
        }
    }
}
