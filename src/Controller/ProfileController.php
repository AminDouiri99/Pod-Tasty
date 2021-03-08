<?php

namespace App\Controller;

use App\Entity\UserInfo;
use App\Form\ProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{id}", name="profile")
     */
    public function index(string $id): Response
    {

        $getUser = $this->getUser();
        $userInfo=$this->getDoctrine()->getRepository(UserInfo::class)->find($id);
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController','user'=>$getUser,'userInfo'=>$userInfo,'id'=>$id
        ]);
    }
    /**
     * @Route("/editprofile", name="editprofile")
     */
    public function editProfile(Request $request){
        $user=$this->getUser();
        $userId=$this->getUser()->getId();
        $userInfo= $this->getDoctrine()->getRepository(UserInfo::class)->find($userId);
        $form= $this->createForm(ProfileType::class,$userInfo);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $img = $form->get('UserImage')->getData();
            if ($img) {
                $originalFilename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                //$safeFilename = $slugger->slug($originalFilename);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$img->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $img->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $userInfo->setUserImage($newFilename);
                $em=$this->getDoctrine()->getManager();
                $em->persist($userInfo);
                $em->flush();
                return $this->render('profile/index.html.twig', [
                    'form1' => $form->createView(),'user'=>$user,'userInfo'=>$userInfo,'file'=>$newFilename]);

            }
        }
    return $this->render('profile/index.html.twig', [
        'form1' => $form->createView(),'user'=>$user,'userInfo'=>$userInfo,'file'=>'3asba']);

    }
}
