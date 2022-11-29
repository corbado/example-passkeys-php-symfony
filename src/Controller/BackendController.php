<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BackendController extends AbstractController
{
    /**
     * @Route("/corbado/loginInfo", name="loginInfo", methods="GET")
     */
    public function loginInfo(Request $request): Response
    {
        $username = $request->query->get('username');

        //Look up in database if $username exists and if $username is blocked/not permitted to login
        $userExists = true;
        $userBlocked = false;

        //Send response
        if ($userBlocked) {
            return new Response(status: 403);
        }
        if (!$userExists) {
            return new Response(status: 404);
        }
        return new Response(status: 200);
    }

    /**
     * @Route("/corbado/sessionToken", name="sessionToken", methods="GET")
     */
    public function sessionToken(Request $request): Response
    {
        $token = $request->query->get('sessionToken');

        $projectId = 'pro-1234';
        $apiSecret = 'xxxx';
        $encoded = base64_encode("$projectId:$apiSecret");
        $authentication = "Basic $encoded";

        $useragent = $request->headers->get('User-Agent');
        $remoteAddress = $request->getClientIp();
        $body = [
            'token' => $token,
            'clientInfo' => [
                "remoteAddress" => "127.0.0.1",
                "userAgent" => "Mozilla"
            ],
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.corbado.com/v1/sessions/verify");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json', "Authorization: $authentication"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        $data = json_decode($response, true)['data'];
        $userData = json_decode($data['userData'], true);
        $username = $userData['username'];
        $userFullName = $userData['userFullName'];

        //Create session for $username
        $session = "your-session";

        //Forward the user to your frontend page
        return new Response("<meta http-equiv='refresh' content='0; url=https://app.your-company.com?session=$session' />");
    }

    public function old(Request $request)
    {
        $token = $request->query->get('sessionToken');

        $projectId = '';
        $apiSecret = '';
        $authentication = 'Basic ' + base64_encode("$projectId: $apiSecret");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.corbado.com/v1/sessions/verify?sessionToken=$token");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json', "Authorization: $authentication"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        $data = json_decode($response);
        print($data);
    }

    /**
     * @Route("/corbado/passwordVerify", name="passwordVerify", methods="POST")
     */
    public function passwordVerify(Request $request): Response
    {
        $parameters = json_decode($request->getContent(), true);
        $username = $parameters['username'];
        $password = $parameters['password'];

        //Check in database if $username and $password match
        $matches = true;

        if ($matches) {
            return new Response(status: 200);
        } else {
            return new Response(status: 400);
        }
    }
}