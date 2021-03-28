<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserInfo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileAdminController extends AbstractController
{
    /**
     * @Route("/profile/admin", name="profile_admin")
     */
    public function index(): Response
    {
        if($this->getUser()->getIsAdmin() == false){
            $this->redirectToRoute("home");
        }
        $userInfoId=$this->getDoctrine()->getRepository(User::class)->find($this->getUser())->getUserInfoId();
        $userInfo=$this->getDoctrine()->getRepository(UserInfo::class)->find($userInfoId);

        return $this->render('back_office/profile.html.twig', [
            'controller_name' => 'ProfileAdminController','userInfo'=>$userInfo
        ]);
    }
    /**
     * @Route("/editprofileadmin", name="editprofileadmin")
     */
    public function editProfile(Request $request)
    {
        $getUser = $this->getUser();
        $userId=$this->getUser()->getId();
        $userInfoId=$this->getDoctrine()->getRepository(User::class)->find($userId)->getUserInfoId();

        $userInfo= $this->getDoctrine()->getRepository(UserInfo::class)->find($userInfoId);
        if (isset($_POST['submit2'])) {
            $fname=$_POST['firstname'];
            $lname=$_POST['lastname'];
            $bio=$_POST['bio'];
            $userInfo->setUserFirstName($fname);
            $userInfo->setUserLastName($lname);
            $userInfo->setUserBio($bio);
            $em=$this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute("profile_admin");

        }

        return $this->redirectToRoute("profile_admin");


    }

}
