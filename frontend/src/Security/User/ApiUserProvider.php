<?php
namespace App\Security\User;
use  App\Entity\ApiUser;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class ApiUserProvider implements UserProviderInterface
{
    public function supportsClass($class)
    {
        return ApiUser::class === $class;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof ApiUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
                );
        }
        return $user;
    }
   
    public function loadUserByUsername($username)
    {
        return new ApiUser($username, ['ROLE_USER']);
    }

}

