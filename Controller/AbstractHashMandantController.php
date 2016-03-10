<?php

namespace Ibrows\Bundle\NewsletterBundle\Controller;

abstract class AbstractHashMandantController extends AbstractController
{
    /**
     * @var string
     */
    protected $mandantName = null;

    /**
     * @return null|string
     */
    protected function getMandantName()
    {
        if (!$this->mandantName) {
            return parent::getMandantName();
        }
        return $this->mandantName;
    }

    /**
     * @param string $hash
     */
    protected function setMandantNameByHash($hash)
    {
        $this->mandantName = $this->getMandantNameByHash($hash);
    }
}
