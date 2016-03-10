<?php

namespace Ibrows\Bundle\NewsletterBundle\Renderer;

class TwigRenderer implements RendererInterface
{
    /**
     * @var \Twig_Environment
     */
    protected $engine;

    /**
     * TwigRenderer constructor.
     * @param array $twigOptions
     */
    public function __construct(array $twigOptions = array())
    {
        $this->engine = new \Twig_Environment(
            null,
            $twigOptions
        );
    }

    /**
     * @param RenderableInterface $element
     * @param array               $parameters
     * @return string
     */
    public function render(RenderableInterface $element, array $parameters = array())
    {
        try {
            $rendered = $this->engine->render($element->getContent(), $parameters);
        } catch (\Exception $e) {
            $rendered = $e->getMessage();
        }
        return $rendered;
    }

}
