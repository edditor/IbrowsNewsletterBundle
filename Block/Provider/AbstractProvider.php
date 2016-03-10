<?php

namespace Ibrows\Bundle\NewsletterBundle\Block\Provider;

use Ibrows\Bundle\NewsletterBundle\Model\Block\BlockInterface;
use Ibrows\Bundle\NewsletterBundle\Service\BlockProviderManager;

abstract class AbstractProvider implements ProviderInterface
{
    /**
     * @var BlockProviderManager
     */
    protected $blockProviderManager;

    /**
     * @param BlockInterface $block
     * @param string         $blockClassName
     * @return $this
     */
    public function initialize(BlockInterface $block, $blockClassName)
    {
        return $this;
    }

    /**
     * @param BlockInterface $block
     * @param mixed          $update
     * @return $this
     */
    public function updateBlock(BlockInterface $block, $update)
    {
        return $this;
    }

    /**
     * @param BlockInterface $block
     * @return $this
     */
    public function updateClonedBlock(BlockInterface $block)
    {
        return $this;
    }

    /**
     * @return array
     */
    public function getOptionKeys()
    {
        return array();
    }

    /**
     * @param BlockProviderManager $blockProviderManager
     */
    public function setBlockProviderManager(BlockProviderManager $blockProviderManager)
    {
        $this->blockProviderManager = $blockProviderManager;
    }

    /**
     * @param BlockInterface $block
     * @return string
     */
    public function getBlockDisplayContent(BlockInterface $block)
    {
        $content = $this->getStartBlockDisplayContent($block);

        foreach ($block->getBlocks() as $childBlock) {
            $content .= $this->getPreBlockDisplayContent($childBlock);

            $childProvider = $this->blockProviderManager->get($childBlock->getProviderName());
            $content .= $childProvider->getBlockDisplayContent($childBlock);

            $content .= $this->getPostBlockDisplayContent($childBlock);
        }

        $content .= $block->getContent();

        return $content . $this->getEndBlockDisplayContent($block);
    }

    /**
     * @param BlockInterface $block
     * @return string
     */
    public function getBlockEditContent(BlockInterface $block)
    {
        $content = $this->getStartBlockEditContent($block);

        foreach ($block->getBlocks() as $childBlock) {
            $content .= $this->getPreBlockEditContent($childBlock);

            $childProvider = $this->blockProviderManager->get($childBlock->getProviderName());
            $content .= $childProvider->getBlockEditContent($childBlock);

            $content .= $this->getPostBlockEditContent($childBlock);
        }

        $content .= $block->getContent();

        return $content . $this->getEndBlockEditContent($block);
    }

    /**
     * @param BlockInterface $block
     * @return string
     */
    protected function getStartBlockDisplayContent(BlockInterface $block)
    {
        return '';
    }

    /**
     * @param BlockInterface $block
     * @return string
     */
    protected function getEndBlockDisplayContent(BlockInterface $block)
    {
        return '';
    }

    /**
     * @param BlockInterface $block
     * @return string
     */
    protected function getPreBlockDisplayContent(BlockInterface $block)
    {
        return '';
    }

    /**
     * @param BlockInterface $block
     * @return string
     */
    protected function getPostBlockDisplayContent(BlockInterface $block)
    {
        return '';
    }

    /**
     * @param BlockInterface $block
     * @return string
     */
    protected function getStartBlockEditContent(BlockInterface $block)
    {
        return '';
    }

    /**
     * @param BlockInterface $block
     * @return string
     */
    protected function getEndBlockEditContent(BlockInterface $block)
    {
        return '';
    }

    /**
     * @param BlockInterface $block
     * @return string
     */
    protected function getPreBlockEditContent(BlockInterface $block)
    {
        return '';
    }

    /**
     * @param BlockInterface $block
     * @return string
     */
    protected function getPostBlockEditContent(BlockInterface $block)
    {
        return '';
    }
}
