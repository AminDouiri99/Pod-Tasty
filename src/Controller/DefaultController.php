<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

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
