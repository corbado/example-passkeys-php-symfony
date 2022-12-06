<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BackendController extends AbstractController
{

    const projectId = "";
    const apiSecret = "";
    const ngrokUrl = "";

    /**
     * @Route("/api/loginInfo", name="loginInfo", methods="GET")
     */
    public function loginInfo(Request $request): Response
    {
        $username = $request->query->get('username');

        $method = $_SERVER['REQUEST_METHOD'];
        var_dump("request method loginInfo: $method");
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
     * Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0
     */
    public function sessionToken(Request $request, SessionInterface $session): Response
    {
        $token = $request->query->get('sessionToken');

        $method = $_SERVER['REQUEST_METHOD'];
        var_dump("request method sessionToken: $method\n");

        var_dump("HIER 1\n");
        $projectId = '';
        $apiSecret = '';
        $encoded = base64_encode("$projectId:$apiSecret");
        var_dump("HIER 2\n");
        $authentication = "Basic $encoded";

        $useragent = $request->headers->get('User-Agent');
        $remoteAddress = $request->getClientIp();
        $remoteAddress2 = system("curl -s ipv4.icanhazip.com");
        $forwarded = $request->headers->get("x-forwarded-for");
        var_dump("RemoteAddress: $remoteAddress\n");
        var_dump("RemoteAddress2: $remoteAddress2\n");
        var_dump("Forwarded: $forwarded\n");
        #$remoteAddress = "212.204.96.162";
        var_dump("HIER 3\n");
        $body = [
            'token' => $token,
            'clientInfo' => [
                "remoteAddress" => $remoteAddress2,
                "userAgent" => $useragent
            ],
        ];
        var_dump("HIER 4\n");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.corbado.com/v1/sessions/verify");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json', "Authorization: $authentication"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        var_dump("HIER 5\n");

        var_dump($response);

        var_dump("HIER 6\n");
        $data = json_decode($response, true)['data'];
        $userData = json_decode($data['userData'], true);
        $username = $userData['username'];
        $userFullName = $userData['userFullName'];
        var_dump("HIER 7\n");

        //Create session for $username

        //Forward the user to your frontend page
        // Set session value
        $value = "$username:$userFullName";
        $request->getSession()->migrate();
        $request->getSession()->set("user", $value);
        //    $request->getSession()->set('user', $value);
        //    $session->set('user', "$username:$userFullName");
        $response = new Response("<meta http-equiv='refresh' content='0; url=https://d15e-212-204-96-162.eu.ngrok.io/' />");
        //$response = new Response("selfnsegn");
        //    $session = $request->getSession();

        return $response;
    }

    /**
     * @Route("/api/passwordVerify", name="passwordVerify", methods="POST")
     */
    public function passwordVerify(Request $request): Response
    {
        $method = $_SERVER['REQUEST_METHOD'];
        var_dump("request method passwordVerify: $method");

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