<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Corbado\SDK;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route('/login', name: 'login', methods: 'GET')]
    public function login(Security $security, string $authenticationUrl): Response
    {
        $sessionUser = $security->getUser();
        if ($sessionUser instanceof User) {
            return $this->redirectToRoute('home');
        }

        return $this->render(
            'login.html.twig',
            array(
                'authenticationUrl' => $authenticationUrl,
            )
        );
    }

    #[Route('/', name: 'home', methods: 'GET')]
    public function home(Security $security, string $authenticationUrl): Response
    {
        $sessionUser = $security->getUser();
        if (!$sessionUser instanceof User) {
            return $this->redirectToRoute('login');
        }

        return $this->render(
            'home.html.twig',
            [
                'authenticationUrl'=> $authenticationUrl,
                'username'=> $sessionUser->getEmail(),
                'userFullName' => $sessionUser->getName(),
            ]
        );

    }

    #[Route('/ping', name: 'pong', methods: 'GET')]
    public function pong(Request $request): Response
    {
        return new Response("pong");
    }
}
