<?php

namespace Ibrows\Bundle\NewsletterBundle\Service\orm;

use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class MandantUserProvider implements UserProviderInterface
{
    /**
     * @var ObjectRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $class;

    /**
     * MandantUserProvider constructor.
     * @param ObjectRepository $repository
     */
    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
        $this->class = $repository->getClassName();
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Security\Core\User.UserProviderInterface::loadUserByUsername()
     * @param string $username
     * @return object|UserInterface
     */
    public function loadUserByUsername($username)
    {
        $user = $this->repository->findOneBy(array('username' => $username));
        if ($user === null) {
            throw new UsernameNotFoundException("The user with username $username does not exist.");
        }

        return $user;
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Security\Core\User.UserProviderInterface::refreshUser()
     * @param UserInterface $user
     * @return UserInterface|void
     */
    public function refreshUser(UserInterface $user)
    {
        // TODO: Auto-generated method stub
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Security\Core\User.UserProviderInterface::supportsClass()
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === $this->class;
    }

}
