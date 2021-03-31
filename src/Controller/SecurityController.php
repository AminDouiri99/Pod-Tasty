<?php

namespace App\Controller;

use App\Entity\UserInfo;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\GithubClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class   SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $getUser = $this->getUser();

//        if ($this->getUser()) {
  //           return $this->redirectToRoute('home');
    //     }else

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        //$isAdmin=$this->getDoctrine()->getRepository(UserInfo::class)->find($getUser->getId());


        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error,'user'=>$getUser]);
    }
    /**
     * @Route("/connect/github",name="github_connect")
     */
    public function connect(ClientRegistry $clientRegistry): RedirectResponse{
        /** @var GithubClient $client*/
        $client=$clientRegistry->getClient('github');
        return $client->redirect(['read:user','user:email']);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
