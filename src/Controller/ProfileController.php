<?php

namespace App\Controller;

use Corbado\Exceptions\AssertException;
use Corbado\Exceptions\ConfigException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Corbado\Config;
use Corbado\SDK;

class ProfileController extends AbstractController
{
    private SDK $corbadoSDK;

    /**
     * @throws ConfigException
     * @throws AssertException
     */
    public function __construct()
    {
        $jwksCache = new FilesystemAdapter();
        $config = new Config($_ENV['CORBADO_PROJECT_ID'], $_ENV['CORBADO_API_SECRET']);
        $config->setJwksCachePool($jwksCache);
        $this->corbadoSDK = new SDK($config);
    }

    #[Route('/profile', name: 'profile')]
    public function showProfile(Request $request): Response
    {
        try {
            $user = $this->corbadoSDK->sessions()->getCurrentUser();
            if (!$user->isAuthenticated()) {
                throw new \Exception('User is not authenticated.');
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Unable to retrieve user information.');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'projectId' => $_ENV['CORBADO_PROJECT_ID'],
        ]);
    }
}
