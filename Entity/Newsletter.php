<?php

namespace Ibrows\Bundle\NewsletterBundle\Entity;

use Ibrows\Bundle\NewsletterBundle\Model\Newsletter\Newsletter as AbstractNewsletter;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

class Newsletter extends AbstractNewsletter
{
    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string")
     */
    protected $subject;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string")
     */
    protected $hash;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", name="sender_mail")
     */
    protected $senderMail;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", name="sender_name")
     */
    protected $senderName;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", name="return_mail")
     */
    protected $returnMail;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $starttime;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    protected $createdAt;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string")
     */
    protected $status;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="text", nullable=true)
     */
    protected $error;
}
