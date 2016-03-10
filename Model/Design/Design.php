<?php

namespace Ibrows\Bundle\NewsletterBundle\Model\Design;

use Ibrows\Bundle\NewsletterBundle\Model\Mandant\MandantInterface;

abstract class Design implements DesignInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var string
     */
    protected $mandant;

    /**
     * Design constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->name;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return MandantInterface
     */
    public function getMandant()
    {
        return $this->mandant;
    }

    /**
     * @param MandantInterface $mandant
     * @return $this
     */
    public function setMandant(MandantInterface $mandant)
    {
        $this->mandant = $mandant;

        return $this;
    }
}
