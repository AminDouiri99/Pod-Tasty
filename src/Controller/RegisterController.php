<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserInfo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Validator\Constraints\DateTime;
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
    public function doTheReg(Request $request){
        $getUser = $this->getUser();


        $error=null;
        $succes=null;

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
            sleep(10);
        }else{
            $newUser=new User();
            $newUser->setUserEmail($email);
            $newUser->setUserPassword($this->encoder->encodePassword($newUser,$pass));
            $newUser->setIsAdmin(false);
            $UserInfo=new UserInfo();
            $UserInfo->setUserFirstName($fname);
            $UserInfo->setUserLastName($lname);
            $datedujour = date("y-m-d H:00:00");
            $datearrondie = new \DateTime($bdate);
            $UserInfo->setUserBirthDate($datearrondie);
            $UserInfo->setUserGender($gender);
            $this->saveUser($newUser,$UserInfo);
            $succes="User succesfully registred";
            sleep(2);
            return $this->redirectToRoute("Home");
        }

    }
        return $this->render('LogReg/register.html.twig',["error"=>$error,"succes"=>$succes, 'user' => $getUser]);

    }

}
