<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackOfficeLoginController extends AbstractController
{
    /**
     * @Route("/dashboard/login", name="back_office_login")
     */
    public function index(): Response
    {
        return $this->render("back_office/login.html.twig");
    }
}
