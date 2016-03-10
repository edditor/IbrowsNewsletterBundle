<?php

namespace Ibrows\Bundle\NewsletterBundle\Block\Provider;

use Ibrows\Bundle\NewsletterBundle\Model\Block\BlockInterface;

class TextAreaAndImageProvider extends AbstractProvider
{
    /**
     * @return array
     */
    public function getOptionKeys()
    {
        return array(
            'textWidth'  => array(
                'required' => true
            ),
            'imageWidth' => array(
                'required' => true
            )
        );
    }

    /**
     * @param BlockInterface $block
     * @param string         $blockClassName
     * @return $this
     */
    public function initialize(BlockInterface $block, $blockClassName)
    {
        /** @var BlockInterface $textAreaBlock */
        $textAreaBlock = new $blockClassName();
        $textAreaBlock->setName($block->getProviderOption('textarea.name'));
        $textAreaBlock->setProviderName('ibrows_newsletter.block.provider.textarea');
        $textAreaBlock->setPosition(1);

        /** @var BlockInterface $imageBlock */
        $imageBlock = new $blockClassName();
        $imageBlock->setName($block->getProviderOption('image.name'));
        $imageBlock->setProviderName('ibrows_newsletter.block.provider.image');
        $imageBlock->setPosition(2);

        $block->addBlocks(array($textAreaBlock, $imageBlock));

        return $this;
    }

    /**
     * @param BlockInterface $block
     * @return string
     */
    protected function getStartBlockDisplayContent(BlockInterface $block)
    {
        return '<table class="provider"><tr class="provider">';
    }

    /**
     * @param BlockInterface $block
     * @return string
     */
    protected function getEndBlockDisplayContent(BlockInterface $block)
    {
        return '</tr></table>';
    }

    /**
     * @param BlockInterface $block
     * @return string
     */
    protected function getPreBlockDisplayContent(BlockInterface $block)
    {
        $widthKey = $block->getProviderName() == 'ibrows_newsletter.block.provider.textarea' ?
            'textWidth' : 'imageWidth';

        $width = $block->getParentBlock()->getProviderOption($widthKey, '50%');

        return '<td class="provider" width="' . $width . '" style="width:' . $width . '">';
    }

    /**
     * @param BlockInterface $block
     * @return string
     */
    protected function getPostBlockDisplayContent(BlockInterface $block)
    {
        return '</td>';
    }

    /**
     * @param BlockInterface $block
     * @return string
     */
    protected function getStartBlockEditContent(BlockInterface $block)
    {
        return '<table class="provider"><tr class="provider">';
    }

    /**
     * @param BlockInterface $block
     * @return string
     */
    protected function getEndBlockEditContent(BlockInterface $block)
    {
        return '</tr></table>';
    }

    /**
     * @param BlockInterface $block
     * @return string
     */
    protected function getPreBlockEditContent(BlockInterface $block)
    {
        $widthKey = $block->getProviderName() == 'ibrows_newsletter.block.provider.textarea' ?
            'textWidth' : 'imageWidth';

        $width = $block->getParentBlock()->getProviderOption($widthKey, '50%');

        return '<td class="provider" width="' . $width . '" style="width:' . $width . '">';
    }

    /**
     * @param BlockInterface $block
     * @return string
     */
    protected function getPostBlockEditContent(BlockInterface $block)
    {
        return '</td>';
    }
}
