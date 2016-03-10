<?php

namespace Ibrows\Bundle\NewsletterBundle\Renderer;

interface RenderableInterface
{
    /**
     * @return string
     */
    public function getContent();
}
