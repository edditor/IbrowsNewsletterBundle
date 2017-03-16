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
    public function renderHeadJavascripts();

    /**
     * @return string
     */
    public function renderHeadStyles();

    /**
     * @return string
     */
    public function renderContent();

    /**
     * @return string
     */
    public function renderTemplate();
}
