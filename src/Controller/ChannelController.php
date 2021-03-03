<?php

namespace App\Controller;

use App\Entity\Channel;
use App\Repository\ChannelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\ChannelType;
use Symfony\Component\Routing\Annotation\Route;


class ChannelController extends AbstractController
{
    /**
     * @Route("/channel", name="channel")
     */
    public function index(): Response
    {
        return $this->render('channel/index.html.twig', [
            'controller_name' => 'ChannelController',
        ]);
    }

    /**
     * @param ChannelRepository $channel
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route ("/AfficheChannels",name="AfficheChannels")
     */
    public function Affiche(ChannelRepository $channel ){
        $repo=$this->getDoctrine()->getRepository(Channel::class);
        $channel=$repo->findAll();
        return $this->render('channel/affiche.html.twig',['channel'=>$channel]);
    }
    /**
     * @param ChannelRepository $channel
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route ("/AfficheChannel/{id}", name="AfficheChannel")
     */
    public function AfficheChannel(ChannelRepository $channel,$id ){
        $repo=$this->getDoctrine()->getRepository(Channel::class);
        $channel=$repo->findBy(['id'=>$id]);
        return $this->render('channel/affiche.html.twig',['channel'=>$channel]);
    }


    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route("/AjoutChannel", name="AjoutChannel")
     */
    function AjoutChannel(Request $request) {
        $channel = new Channel();
        $form=$this->createForm(ChannelType::class, $channel);
        $form->add("Create", SubmitType::class, [
            'attr' => ['class' => 'button_border button_fill button'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $em->persist($channel);
            $em->flush();
            return $this->redirectToRoute("AfficheChannels");
        }
        $title = "Add a new channel ";
        return $this->render("channel/AjoutChannel.html.twig", [
            'f' =>$form->createView(),
            'page_title' => $title
        ]);}


        /**
         * @param Request $request
         * @return RedirectResponse|Response
         * @Route("/UpdateChannel/{id}", name="UpdateChannel")
         */
        function UpdateChannel(ChannelRepository $oldChannel,Request $request, int $id) {
            $repo=$this->getDoctrine()->getRepository(Channel::class);
            $entityManage=$this->getDoctrine()->getManager();
            $oldChannel=$repo->find($id);
            $form=$this->createForm(ChannelType::class, $oldChannel);
            $form->add("Edit", SubmitType::class, [
                'attr' => ['class' => 'button_border button_fill button'],
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em=$this->getDoctrine()->getManager();
                $em->flush();
                return $this->redirectToRoute("AfficheChannels");
            }
            $title = "Update ".$oldChannel->getChannelName();
            return $this->render("channel/AjoutChannel.html.twig", [
                'f' =>$form->createView(),
                'page_title' => $title
            ]);

        }



    /**
     * @Route("/DeleteChannel/{id}", name="DeleteChannel")
     * @param int $id
     * @return RedirectResponse
     */
    function Delete(int $id) {
        $repo=$this->getDoctrine()->getRepository(Channel::class);
        $entityManage=$this->getDoctrine()->getManager();
        $channel=$repo->find($id);
        $entityManage->remove($channel);
        $entityManage->flush();
        return $this->redirectToRoute("AfficheChannels");
    }






}
