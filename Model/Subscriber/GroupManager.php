<?php
namespace Ibrows\Bundle\NewsletterBundle\Model\Subscriber;

abstract class GroupManager implements GroupManagerInterface
{
    protected $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function create()
    {
        return new $this->class();
    }
}
