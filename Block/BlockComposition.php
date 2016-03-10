<?php

namespace Ibrows\Bundle\NewsletterBundle\Block;

use Doctrine\Common\Collections\Collection;
use Ibrows\Bundle\NewsletterBundle\Renderer\RenderableInterface;
use Ibrows\Bundle\NewsletterBundle\Service\BlockProviderManager;

class BlockComposition implements RenderableInterface
{
    /**
     * @var BlockProviderManager
     */
    protected $blockProvider;

    /**
     * @var Collection
     */
    protected $blocks;

    /**
     * BlockComposition constructor.
     * @param BlockProviderManager $blockProvider
     * @param Collection           $blocks
     */
    public function __construct(BlockProviderManager $blockProvider, Collection $blocks)
    {
        $this->blockProvider = $blockProvider;
        $this->blocks = $blocks;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        $content = '';

        foreach ($this->blocks as $block) {
            $content .= $this->blockProvider->get($block->getProviderName())
                ->getBlockDisplayContent($block);
        }

        return $content;
    }
}
