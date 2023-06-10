<?php

namespace App\Security;

use Corbado\SDK;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Twig\Environment;

class CorbadoAuthenticator extends AbstractAuthenticator
{
    private SDK $corbado;
    private RouterInterface $router;

    public function __construct(SDK $corbado, RouterInterface $router)
    {
        $this->corbado = $corbado;
        $this->router = $router;
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

        return new Passport(
            new UserBadge('', function() use ($user) {
                if ($user->isAuthenticated()) {
                    return $user;
                }

                return null;
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
        die('test');
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
       return new RedirectResponse(
            $this->router->generate('login')
       );
    }
}
