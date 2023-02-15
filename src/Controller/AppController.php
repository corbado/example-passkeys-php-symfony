<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Corbado\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function home(Request $request, UserRepository $userRepo): Response
    {
        $userID = $request->getSession()->get('userID');
        if (empty($userID)) {
            return $this->redirectToRoute('login');
        }

        $user = $userRepo->find($userID);
        if ($user === null) {
            $request->getSession()->clear();
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

    #[Route('/api/sessionToken', name: 'sessionToken', methods: 'GET')]
    public function sessionToken(UserRepository $userRepo, Request $request, SessionInterface $session, Client $apiClient): Response
    {
        $token = $request->query->get('sessionToken');
        $useragent = $request->headers->get('User-Agent');
        $remoteAddress = $request->server->get('REMOTE_ADDR');

        try {
            $result = $apiClient->widget()->sessionVerify($token, $remoteAddress, $useragent);
        } catch (\Exception $e) {
            return new Response('Session token expired', 400);
        }
        $response = $result->getData()->getUserData();

        // Parse json response from Corbado request
        $userData = json_decode($response, true);
        $username = $userData['username'];
        $userFullName = $userData['userFullName'];

        // Create user if not exists
        $user = $userRepo->findOneBy(['email' => $username]);
        if ($user === null) {
            $user = new User($userFullName, $username);
            $userRepo->save($user, true);
        }

        $session->set("userID", $user->getId());

        // Forward the user to frontend page
        return $this->redirectToRoute('home');
    }
}
