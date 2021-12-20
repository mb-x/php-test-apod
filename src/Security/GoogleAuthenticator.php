<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class GoogleAuthenticator extends SocialAuthenticator
{

    /**
     * @var ClientRegistry
     */
    private $clientRegistry;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        ClientRegistry $clientRegistry,
        EntityManagerInterface $entityManager,
        RouterInterface $router
    ) {

        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    /**
     * @inheritDoc
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse('/login');
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request)
    {
        return $request->getPathInfo() === '/connect/google/check' && $request->isMethod("GET");
    }

    /**
     * @inheritDoc
     */
    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getGoogleClient());
    }

    /**
     * @inheritDoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $googleUser = $this->getGoogleClient()->fetchUserFromToken($credentials);

        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $googleUser->getEmail()]);

        if (!$user) {
            $user = new User();
            $user->setFullname($googleUser->getName())
                ->setEmail($googleUser->getEmail());

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // TODO: Implement onAuthenticationFailure() method.
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        // TODO: Implement onAuthenticationSuccess() method.
    }

    private function getGoogleClient()
    {
        return $this->clientRegistry->getClient('google');
    }
}