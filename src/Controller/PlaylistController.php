<?php

namespace App\Controller;

use App\Entity\Playlist;
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
        return $this->render('playlist/index.html.twig', [
            'controller_name' => 'PlaylistController',
        ]);
    }








    /**
     * @param PlaylistRepository $playlist
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route ("/AffichePlaylists",name="AffichePlaylists")
     */
    public function Affiche(PlaylistRepository $playlist ){
        $user=$this->getUser();
        $repo=$this->getDoctrine()->getRepository(Playlist::class);
        $playlist=$repo->findAll();
        return $this->render('playlist/playlist.html.twig',['playlist'=>$playlist , 'user'=>$user]);
    }



    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route("/AjoutPlaylist", name="AjoutPlaylist")
     */
    function AjoutPlaylist(Request $request) {
        $playlist = new Playlist();
        $form=$this->createForm(PlaylistType::class, $playlist);
        $form->add("Add", SubmitType::class, [
            'attr' => ['class' => 'button_border button_fill button'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $em->persist($playlist);
            $em->flush();
            return $this->redirectToRoute("AffichePlaylists");
        }
        $title = "Create new playlist ";
        return $this->render("playlist/AjoutPlaylist.html.twig", [
            'f' =>$form->createView(),
            'page_title' => $title
        ]);}



    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route("/UpdatePlaylist/{id}", name="UpdatePlaylist")
     */
    function UpdateChannel(PlaylistRepository $oldPlaylist,Request $request, int $id) {
        $repo=$this->getDoctrine()->getRepository(Playlist::class);
        $entityManage=$this->getDoctrine()->getManager();
        $oldPlaylist=$repo->find($id);
        $form=$this->createForm(PlaylistType::class, $oldPlaylist);
        $form->add("Edit", SubmitType::class, [
            'attr' => ['class' => 'button_border button_fill button'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("AffichePlaylists");
        }
        $title = "Update ".$oldPlaylist->getPlaylistName();
        return $this->render("playlist/AjoutPlaylist.html.twig", [
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
