<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserInfo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterController extends AbstractController
{

    /**
     * @Route("/saveuser",name="saveuser")
     */
    public function saveUser($User,$UserInfo){
        $em=$this->getDoctrine()->getManager();
        $em->persist($UserInfo);
        $em->flush();
        $infoId=$this->getDoctrine()->getRepository(UserInfo::class)->find($UserInfo);
        $User->setUserInfoId($infoId);
        $User->setDesactiveAccount(false);
        $em=$this->getDoctrine()->getManager();
        $em->persist($User);
        $em->flush();

    }
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
    }

    /**
     * @Route("/register",name="register")
     */
    public function doTheReg(Request $request,ValidatorInterface $validator){
        $getUser = $this->getUser();
        $error=null;
        $succes=null;
        $UserInfoErrors=null;
        $UserErrors=null;
        if (isset($_POST['submit'])){
            $fname=$_POST['_firstname'];
            $lname=$_POST['_lastname'];
            $email=$_POST['_email'];
            $pass=$_POST['_password'];
            $confirmpass=$_POST['_confirmpassword'];
            $bdate=$_POST['_birth-start'];
            $gender=$_POST['gender'];
            if($pass!=$confirmpass){
                $error="confirmation password is not the same";
            }else{
                $newUser=new User();
                $newUser->setUserEmail($email);
                $newUser->setUserPassword($this->encoder->encodePassword($newUser,$pass));
                $newUser->setIsAdmin(false);
                $newUser->setDesactiveAccount(false);
                $UserInfo=new UserInfo();
                $UserInfo->setUserFirstName($fname);
                $UserInfo->setUserLastName($lname);
                $datedujour = date("y-m-d H:00:00");
                $datearrondie = new \DateTime($bdate);
                $UserInfo->setUserBirthDate($datearrondie);
                $UserInfo->setUserGender($gender);
                $succes="User succesfully registred";
                sleep(2);
                $UserErrors=$validator->validate($newUser);
                $UserInfoErrors=$validator->validate($UserInfo);
                if(count($UserErrors)==0 && count($UserInfoErrors)==0){
                    $this->saveUser($newUser,$UserInfo);
                    return $this->redirectToRoute("Home");
                }
            }

        }
        return $this->render('LogReg/register.html.twig',["error"=>$error,"succes"=>$succes, 'user' => $getUser,'errors2' => $UserInfoErrors,'errors1'=>$UserErrors]);

    }

    /**
     * @Route("mobile/CheckMail" )
     */
    function CheckMailMobile(Request $request)
    {
        $user=null;
        $user=$this->getDoctrine()->getRepository(User::class)->findOneBy(["UserEmail"=>$request->get("mail")]);
        if($user===null){
            return new Response("user dose'nt exist",Response::HTTP_NO_CONTENT);
        }else{
            return new Response("user exist",Response::HTTP_OK);
        }
    }
    /**
     * @Route("mobile/desactiveAccount" )
     */
    function desactive(Request $request){
        $id=$request->get("id");
        $this->getDoctrine()->getRepository(User::class)->find($id)->setDesactiveAccount(true);
        $this->getDoctrine()->getManager()->flush();
        return new Response("Desactivated",Response::HTTP_OK);
    }

    /**
     * @Route("mobile/updateProfile" )
     */
    function updateProfile(Request $request){
        $user=$this->getDoctrine()->getRepository(User::class)->find($request->get("id"));
        $user->getUserInfoId()->setUserFirstName($request->get("firstanme"));
        $user->getUserInfoId()->setUserLastName($request->get("lastname"));
        $user->getUserInfoId()->setUserBio($request->get("bio"));
        $this->getDoctrine()->getManager()->flush();
        return new Response("updated",Response::HTTP_OK);

    }
    /**
     * @Route("mobile/updatePic" )
     */
    function updatemobilepic(Request $request){
        $profile = $request->files->get("myFile");

        if (empty($profile)) {
            return new Response("No file specified",
                Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
        }


        if ($profile) {
            $originalFilename = pathinfo($profile->getFileName
            (), PATHINFO_FILENAME);
            $id=intval($request->get("id"));
            $newFilename = $originalFilename  . uniqid().'.' . $profile->guessExtension();
            $this->getDoctrine()->getRepository(User::class)->find($id)->getUserInfoId()->setUserImage($newFilename);
            $this->getDoctrine()->getManager()->flush();

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
     * @Route("mobile/getUsers" )
     */
    function getUsersMobile(Request $request,SerializerInterface $serializer){
        $users=$this->getDoctrine()->getRepository(User::class)->findAll();
        $data=$serializer->serialize($users,'json');
        return new Response($data);
    }


    /**
     * @Route("mobile/addUser" )
     */
    function addUserMobile(Request $request){
        $user = new User();
        $user->setUserEmail($request->get("email"));
        $user->setUserPassword($this->encoder->encodePassword($user,$request->get("password")));
        $userInfo = new UserInfo();
        $userInfo->setUserFirstName($request->get("firstname"));
        $userInfo->setUserLastName($request->get("lastname"));
        $userInfo->setUserGender("male");
        $userInfo->setUserBirthDate($datearrondie = new \DateTime());
        $userInfo->setUserImage("avatar.jpg");
        $user->setUserInfoId($userInfo);
        $user->setDesactiveAccount(false);
        $user->setIsAdmin(false);
        $em=$this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return New Response("user added",Response::HTTP_OK);
    }
    /**
     * @Route("mobile/continueReg" )
     */
    function continueReg(Request $request){
        $id= intval($request->get("id"));
        $user=$this->getDoctrine()->getRepository(User::class)->find($id);
        $user->getUserInfoId()->setUserGender($request->get("gender"));
        //$date=strtotime($request->get("birthdate"));
        //$user->getUserInfoId()->setUserBirthDate($date);
        $em=$this->getDoctrine()->getManager();
        $em->flush();
        return New Response("Reg complete",Response::HTTP_OK);
    }
    /**
     * @Route("mobile/getFollowers" )
     */
    function getFollowers(Request $request,SerializerInterface $serializer){
        $id= intval($request->get("id"));
        $nbFollowers=$this->getDoctrine()->getRepository(User::class)->find($id)->getUserInfoId()->getFollowers()->count();
        $json = $serializer->serialize($nbFollowers, 'json');
        return new Response($json);
    }

    /**
     * @Route("mobile/getFollowing" )
     */
    function getFollowing(Request $request,SerializerInterface $serializer){
        $id= intval($request->get("id"));
        $nbFollowers=$this->getDoctrine()->getRepository(User::class)->find($id)->getUserInfoId()->getFollowing()->count();
        $json = $serializer->serialize($nbFollowers, 'json');
        return new Response($json);
    }
}