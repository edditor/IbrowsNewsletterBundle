<?php

namespace Ibrows\Bundle\NewsletterBundle\Model\User;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

interface MandantUserInterface extends AdvancedUserInterface
{
    public function getMandant();
}
