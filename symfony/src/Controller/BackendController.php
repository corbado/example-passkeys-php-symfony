<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\VarDateTimeImmutableType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Corbado\Webhook;
use Corbado\Classes\Models\AuthMethodsDataResponse;
use Throwable;

class BackendController extends AbstractController
{

    /**
     * @Route("/api/ngrokUrl", name="ngrokUrl", methods="GET")
     */
    public function ngrokUrl(UserRepository $userRepo, Request $request): Response
    {
        $url = $request->query->get('url');
        file_put_contents($_ENV["NGROK_FILE"], $url);

        return new Response(status: 200);
    }

    /**
     * @Route("/api/corbado", name="corbado")
     */
    public function corbado(Request $request): Response
    {
        file_put_contents("webhook.log", "corbado() called");
        try {
            // Create new webhook instance with "webhookUsername" and "webhookPassword". Both must be
            // set in the developer panel (https://app.corbado.com) and are used to secure your
            // webhook (this one here) with basic authentication.
            $webhook = new Webhook($_ENV["WEBHOOK_USERNAME"], $_ENV["WEBHOOK_PASSWORD"]);
        
            // Handle authentication so your webhook is secured (basic authentication). If username
            // and/or password are invalid handleAuthentication() will send HTTP status code
            // 401 (Unauthorized) and terminate/exit execution here.
            $webhook->handleAuthentication();
        
            // Check if request has been made with POST. For Corbado webhooks
            // only POST is allowed/used.
            if (!$webhook->isPost()) {
                throw new Exception('Only POST is allowed');
            }
        
            // Get the webhook action and act accordingly. Every Corbado
            // webhook has an action.
            switch ($webhook->getAction()) {
                // Handle the "authMethods" action which basically checks
                // if a user exists on your side/in your database.
                case $webhook::ACTION_AUTH_METHODS:
                    $request = $webhook->getAuthMethodsRequest();
        
                    // Now check if the given user/username exists in your
                    // database and send status. Implement getUserStatus()
                    // function below.
                    $status = $this->userStatus($request->data->username);
                    $webhook->sendAuthMethodsResponse($status);
        
                    break;
        
                // Handle the "passwordVerify" action which basically checks
                // if the given username and password are valid.
                case $webhook::ACTION_PASSWORD_VERIFY:
                    $request = $webhook->getPasswordVerifyRequest();
        
                    // Now check if the given username and password is
                    // valid. Implement verifyPassword() function below.
                    if ($this->verifyPassword($request->data->username, $request->data->password) === true) {
                        $webhook->sendPasswordVerifyResponse(true);
                    } else {
                        $webhook->sendPasswordVerifyResponse(false);
                    }
        
                    break;
        
                default:
                    throw new Exception('Invalid action "' . $webhook->getAction() . '"');
            }
        } catch (Throwable $e) {
            // If something went wrong just return HTTP status
            // code 500. For successful requests Corbado always
            // expects HTTP status code 200. Everything else
            // will be treated as error.
            http_response_code(500);
        
            // We expose the full error message here. Usually you would
            // not do this (security!) but in this case Corbado is the
            // only consumer of your webhook. The error message gets
            // logged at Corbado and helps you and us debugging your
            // webhook.
            echo $e->getMessage();
            echo $e->getTraceAsString();
        }

        return new Response(status: 200, content: "OK HABAADA");
    }

    function userStatus(string $username): string
{
    //$user = $userRepo->findOneBy(['email' => $username]);

    //Look up in database if $username exists and if $username is blocked/not permitted to login

    return AuthMethodsDataResponse::USER_NOT_EXISTS;



    if ($user == null) {
        return AuthMethodsDataResponse::USER_NOT_EXISTS;
    }
    if ($user->isBlocked()) {
        return AuthMethodsDataResponse::USER_BLOCKED;
    }

    return AuthMethodsDataResponse::USER_EXISTS;
}

/**
 * Verify given username and password.
 *
 * !!! MUST BE IMPLEMENTED BY YOU !!!
 *
 * @param string $username
 * @param string $password
 * @return bool
 */
function verifyPassword(string $username, string $password): bool
{
   // $user = $userRepo->findOneBy(['email' => $username]);

        return false;
}

    /**
     * @Route("/api/loginInfo", name="loginInfo", methods="GET")
     */
    public function loginInfo(UserRepository $userRepo, Request $request): Response
    {
        if (!$this->checkBasicAuth($request)) {
            return new Response("Unauthorized", 500);
        }

        $username = $request->query->get('username');
        $user = $userRepo->findOneBy(['email' => $username]);

        //Look up in database if $username exists and if $username is blocked/not permitted to login

        if ($user == null) {
            return new Response(status: 404);
        }
        if ($user->isBlocked()) {
            return new Response(status: 403);
        }

        return new Response(status: 200);
    }

    /**
     * @Route("/api/sessionToken", name="sessionToken", methods="GET")
     */
    public function sessionToken(UserRepository $userRepo, Request $request, SessionInterface $session): Response
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

        //Create user if not exists
        $user = $userRepo->findOneBy(['email' => $username]);
        if ($user == null) {
            $em = $this->doctrine->getManager();
            $em->persist(new User($userFullName, $username));
            $em->flush();
        }

        //Create session for $username
        // Set session value
        $value = "$username:$userFullName";
        $request->getSession()->migrate();
        $request->getSession()->set("user", $value);

        $url = "";
        if (file_exists($_ENV['NGROK_FILE'])) {
            $url = file_get_contents($_ENV['NGROK_FILE']);
        }
        //Forward the user to frontend page
        return new Response(sprintf("<meta http-equiv='refresh' content='0; url=%s/' />", $url));
    }

    /**
     * @Route("/api/passwordVerify", name="passwordVerify", methods="POST")
     */
    public function passwordVerify(UserRepository $userRepo, Request $request): Response
    {
        if (!$this->checkBasicAuth($request)) {
            return new Response("Unauthorized", 500);
        }

        $parameters = json_decode($request->getContent(), true);
        $username = $parameters['username'];
        $password = $parameters['password'];

        $user = $userRepo->findOneBy(['email' => $username]);

        if ($user == null) {
            return new Response(status: 404);
        }

        $matches = $password == $user->getPassword();

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