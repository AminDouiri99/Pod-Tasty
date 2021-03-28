<?php

namespace App\Security;
use App\Repository\UserRepository;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\GithubClient;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Provider\GithubResourceOwner;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class GithubAuthenticator extends SocialAuthenticator
{

    private RouterInterface $router;
    private UserRepository $userRepository;
    private ClientRegistry $clientRegistry;
    public function __construct(RouterInterface $router,ClientRegistry $clientRegistry,UserRepository $userRepository){
        $this->userRepository=$userRepository;
        $this->router = $router;
        $this->clientRegistry=$clientRegistry;
    }
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->router->generate('app_login'));
    }

    public function supports(Request $request)
    {
       return 'oauth_check' === $request->attributes->get('_route')&&$request->attributes->get('service')==='github';
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getClient());
    }

    /**
     * @param AccessToken $credentials
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {   /**@var GithubResourceOwner $githubUser*/
        $githubUser = $this->getClient()->fetchUserFromToken($credentials);
        return $this->userRepository->findOrCreateFromGithubOauth($githubUser);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // TODO: Implement onAuthenticationFailure() method.
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse('/');
    }
    public function getClient ():GithubClient {
        return $this->clientRegistry->getClient('github');
    }
}