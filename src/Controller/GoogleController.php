<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GoogleController extends AbstractController
{

    /**
     * @Route("/connect/google", name="connect_google")
     * @param ClientRegistry $clientRegistry
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function connect(ClientRegistry $clientRegistry)
    {
        return $clientRegistry->getClient('google')->redirect();
    }

    /**
     * @Route("/connect/google/check", name="connect_google_check")
     */
    public function connectCheck()
    {
        if (!$this->getUser()) {
            return new Response('<body>User not found</body>');
        }

        return $this->redirectToRoute("home");
    }
}