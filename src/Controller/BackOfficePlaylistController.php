<?php

namespace App\Controller;

use App\Entity\Channel;
use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\PlaylistType;


class BackOfficePlaylistController extends AbstractController
{
    /**
     * @Route("/backoffice/playlist", name="back_office_playlist")
     */
    public function index(): Response
    {
        return $this->render('back_office/back_office_playlist/affiche.html.twig', [
            'controller_name' => 'BackOfficePlaylistController',
        ]);
    }




    /**
     * @param PlaylistRepository $playlist
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route ("/admin/playlists",name="backoffice_playlist")
     */
    public function AfficheAll(){
        $user=$this->getUser();
        $playlist=$this->getDoctrine()->getRepository(Playlist::class)->findAll();
        return $this->render('/back_office/back_office_playlist/affiche.html.twig',['playlist'=> $playlist]);
    }
}
