<?php

namespace App\Controller;

use App\Entity\UserInfo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{id}", name="profile")
     */
    public function index(string $id): Response
    {
        $getUser = $this->getUser();
        $userInfo=$this->getDoctrine()->getRepository(UserInfo::class)->find($getUser->getId());
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController','user'=>$getUser,'userInfo'=>$userInfo
        ]);
    }
    /**
     * @Route("/addPic", name="addpic")
     */

    public function addPic(){
        sleep(10);
        if(isset($_FILES['image'])){
        $img = $_FILES['file']['temp'];
        $base64 = base64_encode($img);
        $userId=$this->getUser()->getId();
        $userInfo= $this->getDoctrine()->getRepository(UserInfo::class)->find($userId);
        $userInfo->setUserImage($base64);
        $em=$this->getDoctrine()->getManager();
        $em->persist($base64);
        $em->flush();
            return $this->redirectToRoute('login');

        }
    return $this->redirectToRoute('');

    }
}
