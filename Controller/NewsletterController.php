<?php

namespace Ibrows\Bundle\NewsletterBundle\Controller;

use Doctrine\Common\Collections\Collection;
use Ibrows\Bundle\NewsletterBundle\Annotation\Wizard\Annotation as WizardAction;
use Ibrows\Bundle\NewsletterBundle\Annotation\Wizard\AnnotationHandler as WizardActionHandler;
use Ibrows\Bundle\NewsletterBundle\Model\Block\BlockInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Job\MailJob;
use Ibrows\Bundle\NewsletterBundle\Model\Newsletter\NewsletterInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Subscriber\SubscriberGenderTitleInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NewsletterController extends AbstractController
{
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

        $formtype = $this->getClassManager()->getForm('newsletter');
        $designClass = $this->getClassManager()->getModel('design');
        $form = $this->createForm(new $formtype($this->getMandantName(), $designClass), $newsletter);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
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

        if ($request->getMethod() == 'POST') {
            $blockParameters = array();

            $blockPostArray = $request->request->get('block');
            if (!is_array($blockPostArray)) {
                $blockPostArray = array();
            }

            $blockFileArray = $request->files->get('block');
            if (!is_array($blockFileArray)) {
                $blockFileArray = array();
            }

            foreach ($blockPostArray as $blockId => $content) {
                $blockParameters[$blockId] = $content;
            }

            foreach ($blockFileArray as $blockId => $file) {
                $blockParameters[$blockId] = $file;
            }

            $this->updateBlocksRecursive($newsletter->getBlocks(), $blockParameters);

            // clone newsletter blocks?
            $newsletterCloneId = $request->request->get('clone');
            if ($newsletterCloneId) {
                $cloneNewsletter = $this->getNewsletterById($newsletterCloneId);
                if ($cloneNewsletter) {
                    $this->cloneNewsletterBlocks($newsletter, $cloneNewsletter);
                    $this->setNewsletter($newsletter);
                }
            }

            $this->setNewsletter($newsletter);

            if ($request->request->get('continue')) {
                return $this->redirect($this->getWizardActionAnnotationHandler()->getNextStepUrl());
            }
        }

        return $this->render(
            $this->getTemplateManager()->getNewsletter('edit'),
            array(
                'blockProviderManager' => $this->getBlockProviderManager(),
                'newsletter'           => $newsletter,
                'newsletters'          => $this->getMandant()->getNewsletters(),
                'wizard'               => $this->getWizardActionAnnotationHandler(),
            )
        );
    }

    /**
     * @param WizardActionHandler $handler
     * @return RedirectResponse|null
     */
    public function editValidation(WizardActionHandler $handler)
    {
        if (is_null($this->getNewsletter())) {
            return $this->redirect($handler->getStepUrl($handler->getLastValidAnnotation()));
        }

        return null;
    }

    /**
     * @Route("/subscriber", name="ibrows_newsletter_subscriber")
     * @WizardAction(name="subscriber", number=3, validationMethod="subscriberValidation")
     * @param Request $request
     * @return RedirectResponse|Response|true
     */
    public function subscriberAction(Request $request)
    {
        if (($response = $this->getWizardActionValidation()) instanceof Response) {
            return $response;
        }

        $newsletter = $this->getNewsletter();

        $formtype = $this->getClassManager()->getForm('subscriber');
        $subscriberClass = $this->getClassManager()->getModel('subscriber');
        $form = $this->createForm(new $formtype($this->getMandantName(), $subscriberClass, $this->getMandant()), $newsletter);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->setNewsletter($newsletter);

                return $this->redirect($this->getWizardActionAnnotationHandler()->getNextStepUrl());
            }
        }

        return $this->render(
            $this->getTemplateManager()->getNewsletter('subscriber'),
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
    public function subscriberValidation(WizardActionHandler $handler)
    {
        if (is_null($this->getNewsletter())) {
            return $this->redirect($handler->getStepUrl($handler->getLastValidAnnotation()));
        }

        return null;
    }

    /**
     * @Route("/settings", name="ibrows_newsletter_settings")
     * @WizardAction(name="settings", number=4, validationMethod="settingsValidation")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function settingsAction(Request $request)
    {
        if (($response = $this->getWizardActionValidation()) instanceof Response) {
            return $response;
        }

        $formtype = $this->getClassManager()->getForm('sendsettings');

        // get send settings, if null set default values
        $sendSettings = $this->getSendSettings();
        if ($sendSettings === null) {
            $sendSettings = $this->getMandant()->getSendSettings();
            if ($sendSettings === null) {
                $sendSettingsClass = $this->getClassManager()->getModel('sendsettings');
                $sendSettings = new $sendSettingsClass();
            }
        }
        if ($sendSettings->getStarttime() === null) {
            $sendSettings->setStarttime(new \DateTime());
        }

        // set password non required if already defined in send settings
        $password = $sendSettings->getPassword();
        $password_required = $password === null;
        $form = $this->createForm(new $formtype($password_required), $sendSettings);

        if ($request->getMethod() == 'POST') {
            $plainpassword = $this->decryptPassword($password);
            $form->handleRequest($request);

            // set password from send settings if necessary
            $formpassword = $form->get('password')->getData();
            if ($formpassword !== null) {
                $plainpassword = $formpassword;
            }

            if ($form->isValid()) {
                $sendSettings->setPassword($plainpassword);
                $this->setSendSettings($sendSettings);

                return $this->redirect($this->getWizardActionAnnotationHandler()->getNextStepUrl());
            }
        }

        return $this->render(
            $this->getTemplateManager()->getNewsletter('settings'),
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
    public function settingsValidation(WizardActionHandler $handler)
    {
        $newsletter = $this->getNewsletter();

        if (is_null($newsletter)) {
            return $this->redirect($handler->getStepUrl($handler->getLastValidAnnotation()));
        }

        if (count($newsletter->getSubscribers()) <= 0) {
            return $this->redirect($this->generateUrl('ibrows_newsletter_subscriber'));
        }

        return null;
    }

    /**
     * @Route("/summary", name="ibrows_newsletter_summary")
     * @WizardAction(name="summary", number=5, validationMethod="summaryValidation")
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

        if (count($newsletter->getSubscribers()) <= 0) {
            return $this->redirect($this->generateUrl('ibrows_newsletter_subscriber'));
        }

        return null;
    }

    /**
     * @Route("/generate/mailjobs", name="ibrows_newsletter_generate_mail_jobs")
     */
    public function generateMailJobsAction()
    {
        $newsletter = $this->getNewsletter();
        $sendSettings = $this->getSendSettings();
        $mailjobClass = $this->getClassManager()->getModel('mailjob');

        $mandant = $this->getMandant();
        $rendererManager = $this->getRendererManager();
        $rendererName = $this->getMandant()->getRendererName();
        $bridge = $this->getRendererBridge();

        $objectManager = $this->getObjectManager();
        $subscribers = $newsletter->getSubscribers();
        $count = 1;
        $receiverMails = array();

        foreach ($subscribers as $subscriber) {
            $receiverMail = $subscriber->getEmail();

            // If subscriber already in list - do not add again
            if (in_array($receiverMail, $receiverMails)) {
                continue;
            }
            $receiverMails[] = $receiverMail;

            if ($count % $sendSettings->getInterval() === 0) {
                $time = $sendSettings->getStarttime();
                $time->modify('+ 1 minutes');
                $sendSettings->setStarttime(clone $time);
            }

            $body = $rendererManager->renderNewsletter(
                $rendererName,
                $bridge,
                $newsletter,
                $mandant,
                $subscriber
            );

            /* @var $mailjob MailJob */
            $mailjob = new $mailjobClass($newsletter, $sendSettings);
            $mailjob->setBody($body);
            $mailjob->setToMail($receiverMail);

            if ($subscriber instanceof SubscriberGenderTitleInterface) {
                $mailjob->setToName($subscriber->getFirstname() . ' ' . $subscriber->getLastname());
            }

            $mailjob->setStatus(MailJob::STATUS_READY);

            $this->addNewsletterSendLog($newsletter, $subscriber, "Mail ready to send: logged by " . __METHOD__);
            $objectManager->persist($mailjob);
            ++$count;

            //TODO: better memory leak handling
            if ($count % 200 == 0) {
                $objectManager->flush();
                $objectManager->clear();
            };
        }

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
     * @param array                       $blockParameters
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
