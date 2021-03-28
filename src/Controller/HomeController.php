<?php

namespace App\Controller;

use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class HomeController extends AbstractController
{
    /**
     * @Route("homme", name="home")
     */
    public function index(): Response
    {
        $getUser = $this->getUser();
        if($getUser != null){
            if($getUser->getIsAdmin()){
                return $this->redirectToRoute('back_office');

                }
         }
        if($getUser == new CustomUserMessageAuthenticationException()){
            $getUser = null;
        }else

            return $this->render('base.html.twig', [
                'controller_name' => 'HomeController', 'user' => $getUser]);

    }

}
