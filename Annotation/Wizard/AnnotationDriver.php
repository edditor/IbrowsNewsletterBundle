<?php

namespace Ibrows\Bundle\NewsletterBundle\Annotation\Wizard;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class AnnotationDriver
{

    protected $container;
    protected $annotationHandler;
    protected $annotationClassName;

    protected $reader;

    public function __construct(Container $container, AnnotationHandler $annotationHandler, $annotationClassName)
    {
        $this->container = $container;
        $this->annotationHandler = $annotationHandler;
        $this->annotationClassName = $annotationClassName;

        $this->reader = $container->get('annotation_reader');
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controllerArray = $event->getController();
        if (!is_array($controllerArray)) {
            return;
        }

        $controller = $controllerArray[0];
        $methodName = $controllerArray[1];

        $controllerReflection = new \ReflectionClass($controller);
        $annotationBags = $this->getMethodAnnotationBags($controllerReflection, $methodName);

        $this->annotationHandler->handle($event, $annotationBags);
    }

    protected function getMethodAnnotationBags(\ReflectionClass $controller, $currentMethodName)
    {
        $bags = array();

        foreach ($controller->getMethods() as $methodReflection) {
            /** @var Annotation $annotation */
            $annotation = $this->reader->getMethodAnnotation($methodReflection, $this->annotationClassName);

            if ($annotation) {
                if ($methodReflection->getName() == $currentMethodName) {
                    $annotation->setIsCurrentMethod(true);
                }

                $bag = new AnnotationBag(
                    $annotation,
                    $this->reader->getMethodAnnotations($methodReflection)
                );
                $annotation->setAnnotationBag($bag);

                $bags[] = $bag;
            }
        }

        return $bags;
    }
}
