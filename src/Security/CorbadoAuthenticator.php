<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Corbado\SDK;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class CorbadoAuthenticator extends AbstractAuthenticator
{
    private SDK $corbado;
    private UserRepository $userRepository;
    private Security $security;

    public function __construct(SDK $corbado, UserRepository $userRepository, Security $security)
    {
        $this->corbado = $corbado;
        $this->userRepository = $userRepository;
        $this->security = $security;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): bool
    {
        return true;
    }

    public function authenticate(Request $request): Passport
    {
        $user = $this->corbado->session()->getCurrentUser();
        if ($user->isAuthenticated() === false) {
            throw new CustomUserMessageAuthenticationException('User not authenticated');
        }

        // Create user if not exists
        $dbUser = $this->userRepository->findOneBy(['email' => $user->getEmail()]);
        if ($dbUser === null) {
            $dbUser = new User($user->getName(), $user->getEmail());
            $this->userRepository->save($dbUser, true);
        }

        return new Passport(
            new UserBadge($dbUser->getId(), function() use ($dbUser) {
                return $dbUser;
            }),
            new CustomCredentials(
                function () {
                    return true;
                },
                null
            )
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        if ($this->security->getUser() !== null) {
            $this->security->logout(false);
        }

        return null;
    }
}
