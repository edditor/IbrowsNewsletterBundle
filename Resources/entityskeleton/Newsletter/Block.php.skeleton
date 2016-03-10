<?php

namespace {{NAMESPACE}};

use Ibrows\Bundle\NewsletterBundle\Entity\Block as BaseBlock;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="{{TABLE_PREFIX}}_block")
 */
class Block extends BaseBlock
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Newsletter", inversedBy="blocks")
     */
    protected $newsletter;

    /**
     * @ORM\OneToMany(targetEntity="Block", mappedBy="parentBlock", cascade={"persist"})
     * @ORM\OrderBy({"position" = "ASC"})
     **/
    protected $blocks;

    /**
     * @ORM\ManyToOne(targetEntity="Block", inversedBy="blocks", cascade={"persist"})
     **/
    protected $parentBlock;
}
