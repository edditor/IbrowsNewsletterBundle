<?php

namespace Ibrows\Bundle\NewsletterBundle\Controller;

use Ibrows\Bundle\NewsletterBundle\Form\BlockMetadataEditType;
use Ibrows\Bundle\NewsletterBundle\Model\Block\BlockInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/provider")
 */
class ProviderController extends AbstractController
{
    /**
     * @Route("/view/add", name="ibrows_newsletter_provider_view_add")
     * @param Request $request
     * @return Response
     */
    public function editViewAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        $data = $request->request->all();

        if (!isset($data['provider'])) {
            throw $this->createNotFoundException("No provider set");
        }

        if (!isset($data['name'])) {
            throw $this->createNotFoundException("No name set");
        }

        if (!isset($data['position'])) {
            throw $this->createNotFoundException("No position set");
        }

        if (!isset($data['option']) OR !is_array($data['option'])) {
            $data['option'] = array();
        }

        $provider = $this->getBlockProviderManager()->get($data['provider']);
        $newsletter = $this->getNewsletter();

        $blockClassName = $this->getClassManager()->getModel('block');

        /** @var BlockInterface $block */
        $block = new $blockClassName();
        $block->setName($data['name']);
        $block->setProviderOptions($data['option']);
        $block->setProviderName($data['provider']);
        $block->setPosition($data['position']);

        $provider->initialize($block, $blockClassName);
        $newsletter->addBlock($block);

        $this->getMandantManager()->persistNewsletter($this->getMandant()->getName(), $newsletter);

        $html = '<li data-element="block" data-element-id="' . $block->getId() . '" class="block">' .
            $provider->getBlockEditContent($block) .
            '</li>';

        $json = array(
            'html' => $html,
        );

        $response = new Response(json_encode($json));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/blockmetadataedit", name="ibrows_newsletter_blockmetadata_edit")
     * @param Request $request
     * @return Response
     */
    public function blockmetadataeditAction(Request $request)
    {
        if (!$blockId = (int)$request->get('block')) {
            throw $this->createNotFoundException('Need Block-Id Parameter');
        }

        $newsletter = $this->getNewsletter();
        $block = null;

        foreach ($newsletter->getBlocks() as $newsletterBlock) {
            if ($newsletterBlock->getId() == $blockId) {
                $block = $newsletterBlock;
                break;
            }
        }

        if (!$block) {
            throw $this->createNotFoundException('Block #' . $blockId . ' not found');
        }

        if (!$provider = $this->getBlockProviderManager()->get($block->getProviderName())) {
            throw $this->createNotFoundException('BlockProvider not found');
        }

        $form = $this->createForm(new BlockMetadataEditType($provider), $block);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->getMandantManager()->persistNewsletter($this->getMandant()->getName(), $newsletter);

                $json = array(
                    'html' => $provider->getBlockEditContent($block),
                );

                $response = new Response(json_encode($json));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
        }

        return $this->render(
            'IbrowsNewsletterBundle:Newsletter:editblockmetadata.html.twig',
            array(
                'blockId' => $block->getId(),
                'form'    => $form->createView(),
            )
        );
    }

    /**
     * @Route("/blockposition/update", name="ibrows_newsletter_provider_block_position_update")
     * @param Request $request
     * @return Response
     */
    public function blockPositionUpdateAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        $data = $request->request->all();

        if (!isset($data['positions']) || !is_array($data['positions'])) {
            throw $this->createNotFoundException("invalid positions");
        }

        $newsletter = $this->getNewsletter();

        $positions = $data['positions'];
        foreach ($newsletter->getBlocks() as $block) {
            if (isset($positions[$block->getId()])) {
                $block->setPosition($positions[$block->getId()]);
            }
        }

        $this->getMandantManager()->persistNewsletter($this->getMandant()->getName(), $newsletter);

        $response = new Response(json_encode(array('success' => true)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/block/remove", name="ibrows_newsletter_provider_block_remove")
     * @param Request $request
     * @return Response
     */
    public function blockRemoveAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        $blockId = $request->request->get('id');
        if (!$blockId) {
            throw $this->createNotFoundException("invalid id");
        }

        $newsletter = $this->getNewsletter();

        foreach ($newsletter->getBlocks() as $block) {
            if ($block->getId() == $blockId) {
                $newsletter->removeBlock($block);
            }
        }

        $this->getMandantManager()->persistNewsletter($this->getMandant()->getName(), $newsletter);

        $response = new Response(json_encode(array('success' => true)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
