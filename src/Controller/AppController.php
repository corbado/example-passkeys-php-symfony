<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Corbado\Configuration;
use Corbado\SDK;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route('/login', name: 'login', methods: 'GET')]
    public function login(Request $request, string $projectID): Response
    {
        $request->getSession()->remove('user');
        return $this->render(
            'login.html.twig',
            array(
                'projectID' => $projectID,
            )
        );
    }

    #[Route('/', name: 'home', methods: 'GET')]
    public function home(Request $request, UserRepository $userRepo, string $projectID, string $apiSecret): Response
    {
        $jwksCache = new FilesystemAdapter();
        $config = new Configuration($projectID, $apiSecret);
        $config->setJwksCachePool($jwksCache);
        $corbado = new SDK($config);

        $user = $corbado->sessions()->getCurrentUser();
        if ($user->isAuthenticated()) {
            $dbUser = $userRepo->findOneBy(['email' => $user->getEmail()]);
            if ($dbUser === null) {
                $dbUser = new User($user->getName(), $user->getEmail());
                $userRepo->save($dbUser, true);
            }

            return $this->render(
                'home.html.twig',
                array(
                    'username' => $dbUser->getEmail(),
                    'userFullName' => $dbUser->getName(),
                )
            );
        } else {
            return $this->redirectToRoute('login');
        }
    }

    #[Route('/ping', name: 'pong', methods: 'GET')]
    public function pong(Request $request): Response
    {
        return new Response("pong");
    }
}