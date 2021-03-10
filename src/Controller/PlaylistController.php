<?php

namespace App\Controller;

use App\Entity\Channel;
use App\Entity\Playlist;
use App\Form\ChannelType;
use App\Repository\ChannelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\PlaylistRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\PlaylistType;
use Symfony\Component\Routing\Annotation\Route;

class PlaylistController extends AbstractController
{
    /**
     * @Route("/playlist", name="playlist")
     */
    public function index(): Response
    {
        $user=$this->getUser();
        return $this->render('playlist/index.html.twig', [
            'controller_name' => 'PlaylistController',
            'user'=> $user
        ]);
    }








    /**
     * @param PlaylistRepository $playlist
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route ("/",name="")
     */
    public function AfficheAll(PlaylistRepository $playlist ){
        $user=$this->getUser();
        $repo=$this->getDoctrine()->getRepository(Playlist::class);
        $playlist=$repo->findAll();
        return $this->render('playlist/playlist.html.twig',['playlist'=>$playlist , 'user'=>$user]);
    }
    /**
     * @param PlaylistRepository $playlist
     * @param ChannelRepository $channel
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route ("/AffichePlaylists",name="AffichePlaylists")
     */
    public function Affiche(PlaylistRepository $playlist ,ChannelRepository $channel){
        $user=$this->getUser();
        $ChannelId=$user->getChannelId();
        if (isset($ChannelId)){
            $ChannelId=$user->getChannelId();
        $repo=$this->getDoctrine()->getRepository(Playlist::class);
        $playlist=$repo->findBy(['ChannelId'=>$ChannelId]);
        $repoo=$this->getDoctrine()->getRepository(Channel::class);
        $channel=$repoo->findBy(['id'=>$ChannelId]);
              return $this->render('playlist/playlist.html.twig',['playlist'=>$playlist , 'user'=>$user, 'channel'=>$channel]);}
        else {return $this->redirectToRoute("playlist" );}
    }


    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route("/AjoutPlaylist", name="AjoutPlaylist")
     */
    function AjoutPlaylist(Request $request) {
        $playlist = new Playlist();
        $user=$this->getUser();

        $form=$this->createForm(PlaylistType::class, $playlist);
        $form->add("Add", SubmitType::class, [
            'attr' => ['class' => 'contact_button button_fill ml-auto mr-auto'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $playlist->setChannelId($user->getChannelId());
            $em=$this->getDoctrine()->getManager();
            $em->persist($playlist);
            $em->flush();
            return $this->redirectToRoute("AffichePlaylists");
        }
        $title = "Create new playlist ";
        return $this->render("playlist/AjoutPlaylist.html.twig", [
            'user'=>$user,
            'f' =>$form->createView(),
            'page_title' => $title
        ]);}



    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route("/UpdatePlaylist/{id}", name="UpdatePlaylist")
     */
    function UpdateChannel(PlaylistRepository $oldPlaylist,Request $request, int $id) {
        $user=$this->getUser();
        $repo=$this->getDoctrine()->getRepository(Playlist::class);
        $entityManage=$this->getDoctrine()->getManager();
        $oldPlaylist=$repo->find($id);
        $form=$this->createForm(PlaylistType::class, $oldPlaylist);
        $form->add("Edit", SubmitType::class, [
            'attr' => ['class' => 'contact_button button_fill ml-auto mr-auto'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("AffichePlaylists");
        }
        $title = "Update ".$oldPlaylist->getPlaylistName();
        return $this->render("playlist/AjoutPlaylist.html.twig", [
            'user'=>$user,
            'f' =>$form->createView(),
            'page_title' => $title
        ]);

    }

    /**
     * @Route("/DeletePlaylist/{id}", name="DeletePlaylist")
     * @param int $id
     * @return RedirectResponse
     */
    function Delete(int $id) {
        $repo=$this->getDoctrine()->getRepository(Playlist::class);
        $entityManage=$this->getDoctrine()->getManager();
        $playlist=$repo->find($id);
        $entityManage->remove($playlist);
        $entityManage->flush();
        return $this->redirectToRoute("AffichePlaylists");
    }







}
