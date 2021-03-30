<?php

namespace App\Controller;

use App\Entity\UserInfo;
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
        $userInfo=$this->getDoctrine()->getRepository(UserInfo::class)->find($this->getUser());
        return $this->render("back_office/index.html.twig",['userInfo'=>$userInfo]);
    }
}
