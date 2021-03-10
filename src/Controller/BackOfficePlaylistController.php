<?php

namespace App\Controller;

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
     * @Route ("/backoffice/playlist",name="backoffice_playlist")
     */
    public function AfficheAll(PlaylistRepository $playlist ){
        $user=$this->getUser();
        $repo=$this->getDoctrine()->getRepository(Playlist::class);
        $playlist=$repo->findAll();
        return $this->render('back_office/back_office_playlist/affiche.html.twig',['playlist'=>$playlist ]);
    }
}
