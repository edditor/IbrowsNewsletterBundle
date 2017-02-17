<?php

namespace Ibrows\Bundle\NewsletterBundle\Entity;

use Ibrows\Bundle\NewsletterBundle\Model\Subscriber\Subscriber as AbstractSubscriber;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

class Subscriber extends AbstractSubscriber
{
    /**
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $locale;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $gender = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $title = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $lastname;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $companyname;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $hash;
}
