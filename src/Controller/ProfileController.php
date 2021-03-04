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
        /*s
       if (isset($_POST['upload'])) {

          $image = $_FILES['image'];
           $file = md5(uniqid()).'.'.$image->guessExtension();
           $image->move(
               $this->getParameter('images_directory'),
               $file
           );
           $UserInfo = new UserInfo();
           $UserInfo->setUserImage($file);
           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->persist($UserInfo);
           $entityManager->flush();
       $this->redirectToRoute('');
       }*/
        return $this->redirectToRoute('profile');

    }
}
