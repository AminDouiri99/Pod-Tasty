<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserInfo;
use App\Security\LoginFormAuthenticator;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\GithubClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\SerializerInterface;

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
     * @Route("/mobile/login", name="mobile/app_login")
     */
    public function loginMobile(Request $request,UserPasswordEncoderInterface $encoder,SerializerInterface $serializer): Response
    {
        $currentUser = $this->getDoctrine()->getRepository(User::class)->findOneBy(['UserEmail' => $request->get("mail")]);
        if($currentUser==null){
            return new Response("No User specified",
                Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
        }else
            $pass=$request->get("password");
            $status = $encoder->isPasswordValid($currentUser,strval($pass));
        if($status==false){
            return new Response("a",Response::HTTP_NOT_FOUND,['content-type' => 'text/plain']);
        }else
               return new Response("login succces",Response::HTTP_OK);
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
