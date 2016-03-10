<?php

namespace Ibrows\Bundle\NewsletterBundle\Annotation\Wizard;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Routing\Annotation\Route;

class AnnotationHandler
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @var mixed
     */
    protected $validation;

    /**
     * @var AnnotationBag[]
     */
    protected $annotationBags = array();

    /**
     * @var Annotation[]
     */
    protected $annotations = array();

    /**
     * @var Annotation
     */
    protected $currentAnnotation;

    /**
     * @var int
     */
    protected $currentAnnotationKey;

    /**
     * AnnotationHandler constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param FilterControllerEvent $event
     * @param AnnotationBag[]       $annotationBags
     */
    public function handle(FilterControllerEvent $event, $annotationBags)
    {
        usort(
            $annotationBags,
            function (AnnotationBag $a, AnnotationBag $b) {
                return $a->getAnnotation()->getNumber() > $b->getAnnotation()->getNumber();
            }
        );

        $this->annotationBags = array_values($annotationBags);

        $annotations = array();
        foreach ($annotationBags as $annotationBag) {
            $annotations[] = $annotationBag->getAnnotation();
        }
        $this->annotations = $annotations;

        $controllerArray = $event->getController();
        $controller = $controllerArray[0];

        $hasFoundAnyErrors = false;
        $wizardValidation = true;
        $hasFoundCurrentMethod = false;

        /* @var $annotationBag AnnotationBag */
        foreach ($annotationBags as $annotationBag) {
            $annotation = $annotationBag->getAnnotation();

            $validationMethodName = $annotation->getValidationMethod();
            $validation = $controller->$validationMethodName($this);

            if ($validation instanceof Response) {
                $hasFoundAnyErrors = true;
            }

            if (!$wizardValidation instanceof Response && $validation instanceof Response && !$hasFoundCurrentMethod) {
                $wizardValidation = $validation;
            }

            $isValid = ($hasFoundAnyErrors OR $wizardValidation instanceof Response OR $validation instanceof Response) ? false : true;
            $annotation->setIsValid($isValid);

            if (true === $annotation->isCurrentMethod()) {
                $this->currentAnnotation = $annotation;
                $hasFoundCurrentMethod = true;
            }
        }

        $this->validation = $wizardValidation;
    }

    /**
     * @return Annotation|null
     */
    public function getLastValidAnnotation()
    {
        $lastAnnotation = null;

        foreach ($this->annotationBags as $annotationBag) {
            $annotation = $annotationBag->getAnnotation();
            if (!$annotation->isValid()) {
                return $lastAnnotation;
            }

            $lastAnnotation = $annotation;
        }

        return null;
    }

    /**
     * @return Annotation[]
     */
    public function getAnnotations()
    {
        return $this->annotations;
    }

    /**
     * @return true|Response
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getNextStepUrl()
    {
        $currentNumber = $this->currentAnnotation->getNumber();

        foreach ($this->annotations as $annotation) {
            if ($annotation->getNumber() == $currentNumber + 1) {
                return $this->getStepUrl($annotation);
            }
        }

        throw new \InvalidArgumentException("No route found");
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getCurrentStepUrl()
    {
        return $this->getStepUrl($this->currentAnnotation);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getPrevStepUrl()
    {
        $currentNumber = $this->currentAnnotation->getNumber();

        foreach ($this->annotations as $annotation) {
            if ($annotation->getNumber() == $currentNumber - 1) {
                return $this->getStepUrl($annotation);
            }
        }

        throw new \InvalidArgumentException("No route found");
    }

    /**
     * @param Annotation|Route $annotation
     * @return string
     */
    public function getStepUrl(Annotation $annotation)
    {
        foreach ($annotation->getAnnotationBag()->getAnnotations() as $annotation) {
            if ($annotation instanceof Route) {
                return $this->router->generate($annotation->getName());
            }
        }
        throw new \InvalidArgumentException("No route found");
    }
}
