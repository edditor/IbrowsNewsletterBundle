<?php

namespace Ibrows\Bundle\NewsletterBundle\Editor;

/**
 * EditorInterface
 */
interface EditorInterface
{
    /**
     * @return string
     */
    public function __toString();

    /**
     * @return string
     */
    public function renderEditor();

    /**
     * @return string
     */
    public function renderTemplate();
}
