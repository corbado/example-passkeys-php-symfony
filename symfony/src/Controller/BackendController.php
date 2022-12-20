<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Corbado\Client;
use Doctrine\DBAL\Types\VarDateTimeImmutableType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class BackendController extends AbstractController
{

    public function __construct(private ManagerRegistry $doctrine)
    {
    }

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