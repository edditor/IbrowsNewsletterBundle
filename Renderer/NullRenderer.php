<?php

namespace Ibrows\Bundle\NewsletterBundle\Renderer;

class NullRenderer implements RendererInterface
{
    /**
     * @param RenderableInterface $element
     * @param array               $parameters
     * @return string
     */
    public function render(RenderableInterface $element, array $parameters = array())
    {
        return $element->getContent();
    }
}
