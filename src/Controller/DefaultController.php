<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("", name="default")
     */
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/signUp", name="signup")
     */
    public function signUp(): Response
    {
        return $this->render('default/signUp.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }


}
