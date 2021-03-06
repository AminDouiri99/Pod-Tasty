<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackOfficeController extends AbstractController
{
    /**
     * @Route("/admin", name="back_office")
     */
    public function index(): Response
    {

        $getUser = $this->getUser();
        return $this->render("back_office/index.html.twig", ['user'=>$getUser]);
    }
}
