<?php

namespace App\Security;

use Corbado\Classes\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class CorbadoUserProvider implements UserProviderInterface
{
    /**
     * Symfony calls this method if you use features like switch_user
     * or remember_me. If you're not using these features, you do not
     * need to implement this method.
     *
     * @throws UserNotFoundException if the user is not found
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        die('test');
        // Load a User object from your data source or throw UserNotFoundException.
        // The $identifier argument is whatever value is being returned by the
        // getUserIdentifier() method in your User class.
        return new User($identifier, $identifier);
    }

    /**
     * Refreshes the user after being reloaded from the session.
     *
     * When a user is logged in, at the beginning of each request, the
     * User object is loaded from the session and then this method is
     * called. Your job is to make sure the user's data is still fresh by,
     * for example, re-querying for fresh User data.
     *
     * If your firewall is "stateless: true" (for a pure API), this
     * method is not called.
     *
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        // Return a User object after making sure its data is "fresh".
        // Or throw a UserNotFoundException if the user no longer exists.
        return $user;
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass(string $class)
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }
}
