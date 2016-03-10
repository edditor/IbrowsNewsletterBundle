<?php

namespace {{NAMESPACE}};

use Ibrows\Bundle\NewsletterBundle\Entity\Design as BaseDesign;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="{{TABLE_PREFIX}}_design")
 */
class Design extends BaseDesign
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Mandant", inversedBy="designs")
     * @ORM\JoinColumn(name="mandant_id", referencedColumnName="id")
     */
    protected $mandant;
}
