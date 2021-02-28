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
        $form->add("Ajouter", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $em->persist($channel);
            $em->flush();
            return $this->redirectToRoute("AfficheChannels");
        }
        $title = "Ajouter une nouvelle chaine ";
        return $this->render("channel/AjoutChannel.html.twig", [
            'f' =>$form->createView(),
            'page_title' => $title
        ]);


        /**
         * @param Request $request
         * @return RedirectResponse|Response
         * @Route("/UpdateChannel/{id}")
         */
        function UpdateChannel(Request $request, int $id) {
            $repo=$this->getDoctrine()->getRepository(Channel::class);
            $entityManage=$this->getDoctrine()->getManager();
            $oldChannel=$repo->find($id);
            $form=$this->createForm(ChannelType::class, $oldChannel);
            $form->add("Mise à jour", SubmitType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em=$this->getDoctrine()->getManager();
                $em->flush();
                return $this->redirectToRoute("AfficheChannels");
            }
            $title = "Mise à jour ".$oldClass->getName();
            return $this->render("classroom/AjoutChannel.html.twig", [
                'f' =>$form->createView(),
                'page_title' => $title
            ]);

        }



    }


}
