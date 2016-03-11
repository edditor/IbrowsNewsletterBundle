<?php

namespace Ibrows\Bundle\NewsletterBundle\Renderer;

class TwigRenderer implements RendererInterface
{
    /**
     * @var array
     */
    private $twigOptions;

    /**
     * TwigRenderer constructor.
     * @param array $twigOptions
     */
    public function __construct(array $twigOptions = array())
    {
        $this->twigOptions = $twigOptions;
    }

    /**
     * @param RenderableInterface $element
     * @param array               $parameters
     * @return string
     */
    public function render(RenderableInterface $element, array $parameters = array())
    {
        try {
            $engine = new \Twig_Environment(
                new \Twig_Loader_Array(
                    [
                        'newsletter.html' => $element->getContent()
                    ]
                ),
                $this->twigOptions
            );

            return $engine->render('newsletter.html', $parameters);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
