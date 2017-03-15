<?php

namespace Ibrows\Bundle\NewsletterBundle\Service;

use Ibrows\Bundle\NewsletterBundle\Model\Mandant\MandantInterface;
use Ibrows\Bundle\NewsletterBundle\Editor\EditorInterface;

class EditorManager
{
    protected $editors = array();

    public function __construct()
    {
    }

    /**
     * @param  string          $name
     * @param  EditorInterface $editor
     * @return EditorManager
     */
    public function addEditor($name, EditorInterface $editor)
    {
        $this->editors[$name] = $editor;

        return $this;
    }

    /**
     * @param  string $name
     * @return EditorInterface
     * @throws \InvalidArgumentException
     */
    public function get($name)
    {
        if (!array_key_exists($name, $this->editors)) {
            throw new \InvalidArgumentException("The editor service '$name' can not be found.");
        }

        return $this->editors[$name];
    }
}
