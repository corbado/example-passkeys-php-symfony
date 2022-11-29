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

        //Look up emails and phone numbers in database for $username
        $emails = array("$username@gmail.com");
        $phone_numbers = array(
            "+49123456789",
            "+49123456788",
        );

        //Send response
        return new JsonResponse(
            array(
                'methods' => array('email', 'phone_number', 'password'),
                'emails' => $emails,
                'phone_numbers' => $phone_numbers
            )
        );
    }

    /**
     * @Route("/corbado/sessionToken", name="sessionToken", methods="GET")
     */
    public function sessionToken(): Response
    {
        return new Response(
            "sessionToken endpoint HA"
        );
    }

    /**
     * @Route("/corbado/passwordVerify", name="passwordVerify", methods="POST")
     */
    public function passwordVerify(): Response
    {
        return new Response(
            "passwordVerify endpoint HA"
        );
    }
}