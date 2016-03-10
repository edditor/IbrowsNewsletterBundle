<?php

namespace {{NAMESPACE}};

use Ibrows\Bundle\NewsletterBundle\Entity\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="{{TABLE_PREFIX}}_subscriber_group")
 */
class Group extends BaseGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Subscriber", mappedBy="groups")
     */
    protected $subscribers;

    /**
     * @ORM\ManyToOne(targetEntity="Mandant", inversedBy="subscriberGroups")
     */
    protected $mandant;
}
