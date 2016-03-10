<?php

namespace {{NAMESPACE}};

use Ibrows\Bundle\NewsletterBundle\Entity\Mandant as BaseMandant;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="{{TABLE_PREFIX}}_mandant")
 */
class Mandant extends BaseMandant
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Newsletter", mappedBy="mandant")
     */
    protected $newsletters;

    /**
     * @ORM\OneToMany(targetEntity="Subscriber", mappedBy="mandant")
     */
    protected $subscribers;

    /**
     * @ORM\OneToMany(targetEntity="Group", mappedBy="mandant")
     */
    protected $subscriberGroups;

    /**
     * @ORM\OneToMany(targetEntity="Design", mappedBy="mandant")
     */
    protected $designs;
}
