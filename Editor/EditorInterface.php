<?php

namespace Ibrows\Bundle\NewsletterBundle\Editor;

use Ibrows\Bundle\NewsletterBundle\Renderer\RenderableInterface;

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
     * @param RenderableInterface $template
     */
    public function setTemplate(RenderableInterface $template);

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

    /**
     * @param string $content
     * @return string
     */
    public function preLoadContentIntoEditor($content);

    /**
     * @param string $content
     * @return string
     */
    public function preSaveContentFromEditor($content);
}
