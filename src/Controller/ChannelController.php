<?php

namespace App\Controller;

use App\Entity\Channel;
use App\Entity\Playlist;
use App\Entity\User;
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
        return $this->render('channel/afficheChannel.html.twig',['channel'=>$channel, 'user'=>$user]);
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







/**
 *  @param Request $request
 * @return Response
 * @Route ("/filterChannels")
 */
public function FilerChannel(Request $request ,ChannelRepository $ChannelRepo){

    $response = "";

    $channels=$ChannelRepo->findAll();
    foreach($channels as $channel) {
        if ($request->get("text") != "") {
            if (stripos($channel->getChannelName() ,$request->get("text")) === false) {

                unset($channels[array_search($channel,$channels)]);
            } else {

                $response .= $this->getString($channel);
            }
        } else {

            $response .= $this->getString($channel);

        }
    }
    return new Response($response);

}


function getString(Channel $channel){

    $s = '<div class="card">
        <img src="https://lh3.googleusercontent.com/pZwZJ5HIL5iKbA91UGMUIPR0VJWa3K0vOGzDZmY6wU3EJBUdfsby3VEyxU162XxTyOyP3D154tjkr-4Jgcx8lygYUR8eB-jVmld4dsHi1c-mE_A8jKccseAG7bdEwVrcuuk6ciNtSw=s170-no" alt="Person" class="card__image">
        <p class="card__name text-white">'.$channel->getChannelName().'</p>
        <div class="text-white-50">'.$channel->getChannelDescription().'</div>
        <div class="grid-container">

            <div class="grid-child-posts">
                '.$channel->getPlaylists()->count().' Playlists
            </div>

            <div class="grid-child-followers">
                1300 Likes
            </div>

        </div>
        <ul class="social-icons">
            <li><a href="#"><i class="fa fa-instagram"></i></a></li>
            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
            <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
            <li><a href="#"><i class="fa fa-codepen"></i></a></li>
        </ul>
        <button class="btn draw-border">Follow</button>
        <button class="btn draw-border">Visit</button>
    </div>';
    return $s;
}}