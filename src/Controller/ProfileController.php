<?php

namespace App\Controller;

use App\apiModel\FileUploadApiModel;
use App\Entity\Channel;
use App\Entity\Notification;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\ProfileType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FileUploader;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
    private $passwordEncoder;

    /**
     * @Route("/profile/{id}", name="profile")
     */
    public function index(string $id,FileUploader $fileUploader,Request $request): Response
    {
        //$userId=$this->getUser()->getId();
        if($this->getUser()==null){
            $this->redirect("profile/$id");
        }
        $userInfoId=$this->getDoctrine()->getRepository(User::class)->find($id)->getUserInfoId();
        $userInfo= $this->getDoctrine()->getRepository(UserInfo::class)->find($userInfoId);
        $form= $this->createForm(ProfileType::class,$userInfo);

        $form->handleRequest($request);
        if ($form->isSubmitted() ) {
            $img = $form->get('UserImage')->getData();
            if ($img) {
                $userId=$this->getUser();
                $originalFilename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                //$safeFilename = $slugger->slug($originalFilename);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$img->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    /*if($userInfo->getUserImage()!=null){
                        $userOldImg=$userInfo->getUserImage();
                        $filesystem = new Filesystem();
                        $filesystem->remove((['symlink', '../../public/Files/podcastFiles', $userOldImg]));
                    }*/
                    $img->move(
                        $this->getParameter('PODCAST_FILES'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $userInfo=$this->getDoctrine()->getRepository(UserInfo::class)->find($this->getUser()->getUserInfoId());
                $userInfo->setUserImage($newFilename);
                $em=$this->getDoctrine()->getManager();
                $em->persist($userInfo);
                $em->flush();
                return $this->render('profile/index.html.twig', [
                    'controller_name' => 'ProfileController','user'=>$userId,'userInfo'=>$userInfo,'id'=>$id,'form' => $form->createView()
                ]); }
        }
        $getUser = $this->getUser();
        $ChannelId=$getUser->getChannelId();
        if (isset($ChannelId)){$repoo=$this->getDoctrine()->getRepository(Channel::class);
            $channell=$repoo->findOneBy(['id'=>$ChannelId]);
            $channelStatus=$channell->getChannelStatus();} else $channelStatus=3;

 //       $userInfo=$this->getDoctrine()->getRepository(UserInfo::class)->find($id);
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController','user'=>$getUser,'userInfo'=>$userInfo,'channelStatus'=>$channelStatus,'id'=>$id,'form' => $form->createView()
        ]);
    }
    /**
     * @Route("api/profile/pic/post", name="profilePicPost", methods={"POST"})
     */
    public function postProfilePic(Request $request)
    {
        $profile = $request->files->get("myFile");

        if (empty($profile)) {
                return new Response("No file specified",
                    Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
            }


        if ($profile) {
            $originalFilename = pathinfo($profile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename  . '.' . $profile->guessExtension();
            try {
                $profile->move(
                    $this->getParameter('PODCAST_FILES'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            return new Response("FILE UPLOADED",
                Response::HTTP_OK, ['content-type' => 'text/plain']);
        }
    }

    /**
     * @Route("/editprofile", name="editprofile")
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

            return $this->redirect("profile/$userId");

        }

        return $this->redirect("profile/$userId");


    }
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
    }

    /**
     * @Route("/editpwd", name="editpwd")
     */
    public function editPwd(Request $request)
    {   $userId=$this->getUser()->getId();
        $user=$this->getUser();
        if (isset($_POST['submitPwd'])) {
           $oldpwd=$_POST['oldPwd'];
           if($this->encoder->isPasswordValid($user,$oldpwd))
               {
               $newPwd=$_POST['newPwd'];
               $user->setUserPassword($this->encoder->encodePassword($user,$newPwd));
               $em=$this->getDoctrine()->getManager();
               $em->flush();

           }else{
               return $this->redirectToRoute("home");
           }
        }
        return $this->redirect("Home");

    }

         /**
          * @Route("/desactiveaccount", name="desactive")
          */
    public function desactive(){
        $userId=$this->getUser()->getId();
        $User = $this->getUser();
        $em=$this->getDoctrine()->getManager();
        $User->setDesactiveAccount(true);
        $em->flush();

        return $this->redirect("profile/$userId");
    }
    /**
     * @Route("/activeaccount", name="active")
     */
    public function active(){
        $userId=$this->getUser()->getId();
        $User = $this->getUser();
        $em=$this->getDoctrine()->getManager();
        $User->setDesactiveAccount(false);
        $em->flush();

        return $this->redirect("profile/$userId");
    }
    /**
     * @Route("/follow/{id}", name="follow")
     */
    public function follow(string $id,PublisherInterface $publisher){
    $userToAdd=$this->getDoctrine()->getRepository(User::class)->find($id)->getUserInfoId();
        $em=$this->getDoctrine()->getManager();

        $userinfo=$this->getUser()->getUserInfoId()->addFollowing($userToAdd);
        $em->flush();
        return $this->redirect("/profile/$id");
    }
    /**
     * @Route("/unfollow/{id}", name="unfollow")
     */
    public function unfollow(string $id){
        $userToAdd=$this->getDoctrine()->getRepository(User::class)->find($id)->getUserInfoId();
        $em=$this->getDoctrine()->getManager();

        $userinfo=$this->getUser()->getUserInfoId()->removeFollowing($userToAdd);
        $em->flush();

        return $this->redirect("/profile/$id");
    }

    /* /**
      * @Route("/editprofilepic", name="editprofilepic")
      */
  /*  public function editProfilePic(Request $request){
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

    }*/
}
