<?php

namespace App\Controller;

use App\Entity\Channel;
use App\Entity\Playlist;
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
     * @Route("/admin/channels", name="channelsAdmin")
     */
    public function indexadmin(): Response
    {
        $user=$this->getUser();
        $repo=$this->getDoctrine()->getRepository(Channel::class);
        $channel=$repo->findAll();
        return $this->render('back_office/back_office_channel/channel.html.twig', [
            'channel' => $channel,
        ]);
    }

    /**
     * @param ChannelRepository $channel
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route ("/AfficheChannels",name="AfficheChannels")
     */
    public function Affiche(ChannelRepository $channel ){
        $user=$this->getUser();
        $repo=$this->getDoctrine()->getRepository(Channel::class);
        $channel=$repo->findAll();
        return $this->render('channel/affiche.html.twig',['channel'=>$channel, 'user'=>$user]);
    }
    /**
     * @param ChannelRepository $channel
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route ("/AfficheChannel/{id}", name="AfficheChannel")
     */
    public function AfficheChannel(ChannelRepository $channel,$id ){
        $user=$this->getUser();
        $repo=$this->getDoctrine()->getRepository(Channel::class);
        $channel=$repo->findBy(['id'=>$id]);
        return $this->render('channel/affiche.html.twig',['channel'=>$channel, 'user'=>$user]);
    }


    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route("/AjoutChannel", name="AjoutChannel")
     */
    public function AjoutChannel(Request $request) {
        $user=$this->getUser();
        $channel = new Channel();
        $form=$this->createForm(ChannelType::class, $channel);
        $form->add("Create", SubmitType::class, [
            'attr' => ['class' => 'contact_button button_fill ml-auto mr-auto'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setChannelId($channel->getId());
            $em=$this->getDoctrine()->getManager();
            $em->persist($channel);
            $em->flush();
            return $this->redirectToRoute("AffichePlaylists");
        }
        $title = "Add a new channel ";
        return $this->render("channel/AjoutChannel.html.twig", [
            'f' =>$form->createView(),
            'page_title' => $title, 'user'=>$user
        ]);}


        /**
         * @param Request $request
         * @return RedirectResponse|Response
         * @Route("/UpdateChannel/{id}", name="UpdateChannel")
         */
        function UpdateChannel(ChannelRepository $oldChannel,Request $request, int $id) {
            $user=$this->getUser();
            $ChannelId=$user->getChannelId();
            $repo=$this->getDoctrine()->getRepository(Channel::class);
            $entityManage=$this->getDoctrine()->getManager();
            $oldChannel=$repo->find($id);
            $repo=$this->getDoctrine()->getRepository(Playlist::class);
            $playlist=$repo->findBy(['ChannelId'=>$ChannelId]);
            $form=$this->createForm(ChannelType::class, $oldChannel);
            $form->add("Edit", SubmitType::class, [
                'attr' => ['class' => 'contact_button button_fill ml-auto mr-auto'],
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em=$this->getDoctrine()->getManager();
                $em->flush();
                $repoo=$this->getDoctrine()->getRepository(Channel::class);
                $channel=$repoo->findBy(['id'=>$ChannelId]);
                return $this->render("playlist/playlist.html.twig", [
                    'f' =>$form->createView(), 'user'=>$user , 'channel'=>$channel,
                    'page_title' => 'updated' , 'playlist'=>$playlist
                ]);
            }
            $title = "Update ".$oldChannel->getChannelName();
            return $this->render("channel/AjoutChannel.html.twig", [
                'f' =>$form->createView(), 'user'=>$user ,
                'page_title' => $title
            ]);

        }


    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route("/admin/UpdateChannelAdmin/{id}", name="UpdateChannelAdmin")
     */
    function UpdateChannelAdmin(ChannelRepository $oldChannel,Request $request, int $id) {
        $user=$this->getUser();
        $ChannelId=$user->getChannelId();
        $repo=$this->getDoctrine()->getRepository(Channel::class);
        $entityManage=$this->getDoctrine()->getManager();
        $oldChannel=$repo->find($id);
        $repo=$this->getDoctrine()->getRepository(Playlist::class);
        $playlist=$repo->findBy(['ChannelId'=>$ChannelId]);
        $form=$this->createForm(ChannelType::class, $oldChannel);
        $form->add("Edit", SubmitType::class, [
            'attr' => ['class' => 'btn btn-primary'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            $repoo=$this->getDoctrine()->getRepository(Channel::class);
            $channel=$repoo->findBy(['id'=>$ChannelId]);
            return $this->redirectToRoute("channelsAdmin");
        }
        $title = "Update ".$oldChannel->getChannelName();
        return $this->render("back_office/back_office_channel/update.html.twig", [
            'f' =>$form->createView(), 'user'=>$user ,
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
        return $this->redirectToRoute("/");
    }

    /**
     * @Route("/admin/DeleteChannelAdmin/{id}", name="DeleteChannelAdmin")
     * @param int $id
     * @return RedirectResponse
     */
    function DeleteAdmin(int $id) {
        $repo=$this->getDoctrine()->getRepository(Channel::class);
        $entityManage=$this->getDoctrine()->getManager();
        $channel=$repo->find($id);
        $entityManage->remove($channel);
        $entityManage->flush();
        return $this->redirectToRoute("channelsAdmin");
    }




    /**
     * @Route("/channel", name="getCurrentChannel")
     */
public function getCurrentChannel(){

}


}
