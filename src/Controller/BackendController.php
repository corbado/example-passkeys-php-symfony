<?php
namespace App\Controller;

use Corbado\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class BackendController extends AbstractController
{
    /**
     * @Route("/api/loginInfo", name="loginInfo", methods="GET")
     */
    public function loginInfo(Request $request): Response
    {
        if (!$this->checkBasicAuth($request)) {
            return new Response("Unauthorized", 500);
        }

        //Look up in database if $username exists and if $username is blocked/not permitted to login
        $userExists = false;
        $userBlocked = false;

        //Send response

        if (!$userExists) {
            return new Response(status: 404);
        }
        if ($userBlocked) {
            return new Response(status: 403);
        }
        return new Response(status: 200);
    }

    /**
     * @Route("/api/sessionToken", name="sessionToken", methods="GET")
     */
    public function sessionToken(Request $request, SessionInterface $session): Response
    {
        $token = $request->query->get('sessionToken');
        $useragent = $request->headers->get('User-Agent');
        $remoteAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];

        $apiClient = new Client("https://api.corbado.com/v1", $_ENV["PROJECT_ID"], $_ENV["API_SECRET"]);
        $result = $apiClient->widget()->sessionVerify($token, $remoteAddress, $useragent);
        $response = $result->getData()->getUserData();

        //Parse json response from Corbado request
        $userData = json_decode($response, true);
        $username = $userData['username'];
        $userFullName = $userData['userFullName'];

        //Create session for $username
        // Set session value
        $value = "$username:$userFullName";
        $request->getSession()->migrate();
        $request->getSession()->set("user", $value);

        //Forward the user to frontend page
        return new Response(sprintf("<meta http-equiv='refresh' content='0; url=%s/' />", $_ENV['NGROK_URL']));
    }

    /**
     * @Route("/api/passwordVerify", name="passwordVerify", methods="POST")
     */
    public function passwordVerify(Request $request): Response
    {
        if (!$this->checkBasicAuth($request)) {
            return new Response("Unauthorized", 500);
        }

        $parameters = json_decode($request->getContent(), true);
        $username = $parameters['username'];
        $password = $parameters['password'];

        //Check in database if $username and $password match
        $matches = $password == "1234";

        if ($matches) {
            return new Response(status: 200);
        } else {
            return new Response(status: 400);
        }
    }

    private function checkBasicAuth(Request $request): bool
    {
        $username = $request->headers->get('php-auth-user');
        $password = $request->headers->get('php-auth-pw');

        return $username == $_ENV['HTTP_BASIC_AUTH_USERNAME'] and $password == $_ENV['HTTP_BASIC_AUTH_PASSWORD'];
    }
}