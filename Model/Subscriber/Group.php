<?php

namespace Ibrows\Bundle\NewsletterBundle\Model\Subscriber;

use Ibrows\Bundle\NewsletterBundle\Model\Mandant\MandantInterface;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

abstract class Group implements GroupInterface
{
    protected $id;
    protected $subscribers;
    protected $mandant;


    /**
     * @var string $name
     * @Assert\NotBlank(groups={"group"})
     */
    protected $name;

    public function __construct()
    {
        $this->subscribers = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getSubscribers()
    {
        return $this->subscribers;
    }

    public function addSubscriber(SubscriberInterface $subscriber)
    {
        if (!$this->getSubscribers()->contains($subscriber)) {
            $subscriber->addGroup($this);
            $this->subscribers->add($subscriber);
        }

        return $this;
    }

    public function removeSubscriber(SubscriberInterface $subscriber)
    {
        $subscriber->removeGroup($this);
        $this->subscribers->removeElement($subscriber);

        return $this;
    }

    public function getMandant()
    {
        return $this->mandant;
    }

    public function setMandant(MandantInterface $mandant)
    {
        $this->mandant = $mandant;

        return $this;
    }
}
