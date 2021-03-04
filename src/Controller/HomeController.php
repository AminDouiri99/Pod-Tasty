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
     * @Route("", name="Home")
     */
    public function index(): Response

    {
        $getUser = $this->getUser();
        if($getUser == new CustomUserMessageAuthenticationException()){
            $getUser = null;
             }else
            return $this->render("home/home.html.twig", ['user'=>$getUser,]);


    }

}
