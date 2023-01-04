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
            $userRepo->save(new User($userFullName, $username), true);
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
}