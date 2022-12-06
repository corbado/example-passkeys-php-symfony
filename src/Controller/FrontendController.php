<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FrontendController extends AbstractController
{
    /**
     * @Route("/login", name="login", methods="GET")
     */
    public function login(Request $request): Response
    {
        $request->getSession()->remove('user');
        return $this->render('login.html.twig');
    }

    /**
     * @Route("/", name="home", methods="GET")
     */
    public function home(Request $request): Response
    {
        $user = $request->getSession()->get('user');
        if (empty($user)) {
            return new Response("<meta http-equiv='refresh' content='0; url=http://localhost:8000/login' />");
        }

        $split = explode(":", $user);
        $username = $split[0];
        $userFullName = $split[1];
        return $this->render(
            'home.html.twig',
            array(
                'username' => $username,
                'userFullName' => $userFullName
            )
        );

    }
}