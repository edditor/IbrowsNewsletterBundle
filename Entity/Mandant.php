<?php

namespace Ibrows\Bundle\NewsletterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ibrows\Bundle\NewsletterBundle\Model\Mandant\Mandant as AbstractMandant;
use Ibrows\Bundle\NewsletterBundle\Model\Newsletter\SendSettingsInterface;

class Mandant extends AbstractMandant
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $hash;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $salt;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="renderer_name")
     */
    protected $rendererName;

    /**
     * @var SendSettingsInterface
     *
     * @ORM\OneToOne(targetEntity="SendSettings")
     * @ORM\JoinColumn(name="send_settings_id", referencedColumnName="id")
     */
    protected $sendSettings;
}
