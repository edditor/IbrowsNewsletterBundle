<?php

namespace {{NAMESPACE}};

use Ibrows\Bundle\NewsletterBundle\Entity\Newsletter as BaseNewsletter;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *      name="ibrows_newsletter_newsletter",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="unique_idx", columns={"hash"})}
 * )
 */
class Newsletter extends BaseNewsletter
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Mandant", inversedBy="newsletters")
     * @ORM\JoinColumn(name="mandant_id", referencedColumnName="id")
     */
    protected $mandant;

    /**
     * @ORM\ManyToOne(targetEntity="Design")
     * @ORM\JoinColumn(name="design_id", referencedColumnName="id")
     */
    protected $design;

    /**
     * @ORM\ManyToMany(targetEntity="Subscriber", inversedBy="newsletters")
     * @ORM\JoinTable(name="ibrows_newsletter_newsletters_subscribers")
     */
    protected $subscribers;

    /**
     * @ORM\OneToMany(targetEntity="Block", mappedBy="newsletter", cascade={"persist"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $blocks;

    /**
    * @ORM\OneToOne(targetEntity="SendSettings", cascade={"persist"})
    * @ORM\JoinColumn(name="send_settings_id", referencedColumnName="id")
    */
    protected $sendSettings;
}
