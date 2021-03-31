<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserInfo;
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
        $userInfoId=$this->getDoctrine()->getRepository(User::class)->find($this->getUser())->getUserInfoId();
        $userInfo=$this->getDoctrine()->getRepository(UserInfo::class)->find($userInfoId);
        return $this->render("back_office/index.html.twig", ['userInfo'=>$userInfo,'user'=>$this->getUser()]);
    }
}
