<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\UserType;
use App\Repository\UserInfoRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
class UserAdminController extends AbstractController
{
    /**
     * @Route("admin/users", name="users", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        if($this->getUser()->getIsAdmin() == false){
            $this->redirectToRoute("home");
        }
        $userInfoId=$this->getDoctrine()->getRepository(User::class)->find($this->getUser())->getUserInfoId();
        $userInfo=$this->getDoctrine()->getRepository(UserInfo::class)->find($userInfoId);

        return $this->render('back_office/user/index.html.twig', [
            'users' => $userRepository->findAll(),'user'=>$this->getUser(),'userInfo'=>$userInfo
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('back_office/user_index');
        }

        return $this->render('back_office/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        if($this->getUser()->getIsAdmin() == false){
            $this->redirectToRoute("home");
        }
        $userInfoId=$this->getDoctrine()->getRepository(User::class)->find($this->getUser())->getUserInfoId();
        $userInfo=$this->getDoctrine()->getRepository(UserInfo::class)->find($userInfoId);

        return $this->render('back_office/user/show.html.twig', [
            'user' => $user,'userInfo'=>$userInfo
        ]);
    }

    /**
     * @Route("admin/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $userInfoId=$this->getDoctrine()->getRepository(User::class)->find($this->getUser())->getUserInfoId();
        $userInfo=$this->getDoctrine()->getRepository(UserInfo::class)->find($userInfoId);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('users');
        }

        return $this->render('back_office/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'userInfo'=>$userInfo
        ]);
    }

    /**
     * @Route("admin/delete/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('users');
    }


    /*MOBILE APIS*/


    /**
     * @Route("/mobile/getUserById/{id}")
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @param $id
     * @return Response
     */
    function getUserByIdMobile(UserRepository $userRepository,  SerializerInterface $serializer, $id): Response
    {
        $user = $userRepository->findOneBy(["id"=>$id]);
        $json = $serializer->serialize($user, 'json',["groups"=>'users']);
        return new Response($json);
    }
    /**
     * @Route("/mobile/getUserByMail/{mail}" )
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @param $mail
     * @return Response
     */
    function getUserByMailMobile(UserRepository $userRepository,  SerializerInterface $serializer, $mail): Response
    {
        $user = $userRepository->findOneBy(["UserEmail"=>$mail]);
        $user->setUserInfoIdForMobile($user->getUserInfoId()->getId());
        $json = $serializer->serialize($user, 'json',["groups"=>'users']);
        return new Response($json);
    }

    /**
     * @Route("/mobile/getUserInfoById/{id}")
     * @param UserInfoRepository $userRepository
     * @param SerializerInterface $serializer
     * @param $id
     * @return Response
     */
    function getUserInfoByIdMobile(UserInfoRepository $userRepository,  SerializerInterface $serializer, $id): Response
    {
        $userInfo = $userRepository->findOneBy(["id"=>$id]);
        $json = $serializer->serialize($userInfo, 'json',["groups"=>'userInfo']);
        return new Response($json);    }


}