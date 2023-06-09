<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Corbado\Client;
use Corbado\SDK;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route('/login', name: 'login', methods: 'GET')]
    public function login(string $projectID): Response
    {
        return $this->render(
            'login.html.twig',
            array(
                'projectID' => $projectID,
            )
        );
    }

    #[Route('/', name: 'home', methods: 'GET')]
    public function home(UserRepository $userRepo, Security $security,): Response
    {
        $sessionUser = $security->getUser();
        if ($sessionUser === null) {
            return $this->redirectToRoute('login');
        }

        return $this->render(
            'home.html.twig',
            array(
                'username'=> '',
                'userFullName' => '',
            )
        );

    }

    #[Route('/ping', name: 'pong', methods: 'GET')]
    public function pong(Request $request): Response
    {
        return new Response("pong");
    }

    #[Route('/corbadoAuthenticationHandler', name: 'corbadoAuthenticationHandler', methods: 'GET')]
    public function corbadoAuthenticationHandler(UserRepository $userRepo, SDK $corbado): Response
    {
        $sessionUser = $corbado->session()->getCurrentUser();
        if (!$sessionUser->isAuthenticated()) {
            return new Response('User not authenticated', 400);
        }

        // Create user if not exists
        $user = $userRepo->findOneBy(['email' => $sessionUser->getEmail()]);
        if ($user === null) {
            $user = new User($sessionUser->getName(), $sessionUser->getEmail());
            $userRepo->save($user, true);
        }

        // Forward the user to frontend page
        return $this->redirectToRoute('home');
    }
}
