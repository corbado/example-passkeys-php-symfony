<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Corbado\Client;
use Corbado\SDK;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function home(UserRepository $userRepo, SDK $corbado): Response
    {
        $sessionUser = $corbado->session()->getCurrentUser();
        if (!$sessionUser->isAuthenticated()) {
            return $this->redirectToRoute('login');
        }

        // @tobias: brauchen wir das unbedingt? ist bisschen doof weil hier session removed wurde
        // aber das können wir ja jetzt nicht mehr (weil nur im frontend geht). ist im grunde nur für den
        // case wenn ihc es iwi schaffe mich einzuloggen ohne über /corbadoAuthenticationHandler zu gehen, weil
        // dann hier der user fehlt?
        $user = $userRepo->find($sessionUser->getID());
        if ($user === null) {
            return $this->redirectToRoute('login');
        }

        return $this->render(
            'home.html.twig',
            array(
                'username' => $user->getEmail(),
                'userFullName' => $user->getName(),
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
