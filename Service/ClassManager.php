<?php

namespace Ibrows\Bundle\NewsletterBundle\Service;

use Ibrows\Bundle\NewsletterBundle\Model\ClassManagerInterface;

class ClassManager implements ClassManagerInterface
{
    private $models;
    private $forms;

    public function __construct($models, $forms)
    {
        $this->models = $models;
        $this->forms = $forms;
    }

    public function getModel($name)
    {
        if (!array_key_exists($name, $this->models)) {
            throw new \InvalidArgumentException("The model class '$name' can not be found.");
        }

        return $this->models[$name];
    }

    public function getForm($name)
    {
        if (!array_key_exists($name, $this->forms)) {
            throw new \InvalidArgumentException("The form class '$name' can not be found.");
        }

        return $this->forms[$name];
    }
}
