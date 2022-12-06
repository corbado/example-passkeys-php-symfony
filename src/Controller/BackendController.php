<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BackendController extends AbstractController
{

    /**
     * @Route("/api/loginInfo", name="loginInfo", methods="GET")
     */
    public function loginInfo(Request $request): Response
    {
        $username = $request->query->get('username');

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

        //Prepare Corbado request header
        $encoded = base64_encode(sprintf("%s:%s", $_ENV['PROJECT_ID'], $_ENV['API_SECRET']));
        $authentication = "Basic $encoded";
        $useragent = $request->headers->get('User-Agent');
        $remoteAddress = system("curl -s ipv4.icanhazip.com");

        //Prepare Corbado request body
        $body = [
            'token' => $token,
            'clientInfo' => [
                "remoteAddress" => $remoteAddress,
                "userAgent" => $useragent
            ],
        ];

        //Execute Corbado request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.corbado.com/v1/sessions/verify");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json', "Authorization: $authentication"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        //Parse json response from Corbado request
        $data = json_decode($response, true)['data'];
        $userData = json_decode($data['userData'], true);
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
}