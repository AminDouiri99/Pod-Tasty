<?php

namespace App\Controller;

use App\Entity\Channel;
use App\Entity\Playlist;
use App\Entity\User;
use App\Form\ChannelType;
use App\Repository\ChannelRepository;
use App\Repository\UserRepository;
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
     * @Route ("/admin/playlists",name="backoffice_playlist")
     */
    public function AfficheAllAdmin(){
        $user=$this->getUser();
        $playlist=$this->getDoctrine()->getRepository(Playlist::class)->findAll();
        return $this->render('/back_office/back_office_playlist/affiche.html.twig',['playlist'=> $playlist]);
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
     * @Route ("/AfficheChannel",name="AffichePlaylists")
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

    /**
     * @Route("/admin/DeletePlaylistAdmin/{id}", name="DeletePlaylistAdmin")
     * @param int $id
     * @return Response
     */
    function DeleteAdmin(int $id) {
        $repo=$this->getDoctrine()->getRepository(Playlist::class);
        $entityManage=$this->getDoctrine()->getManager();
        $playlist=$repo->find($id);
        $entityManage->remove($playlist);
        $entityManage->flush();
        $playlist=$repo->findAll();
        return $this->redirectToRoute("backoffice_playlist");
    }




    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route("/admin/UpdatePlaylistAdmin/{id}", name="UpdatePlaylistAdmin")
     */
    function UpdateChannelAdmin(PlaylistRepository $oldPlaylist,Request $request, int $id) {
        $user=$this->getUser();
        $repo=$this->getDoctrine()->getRepository(Playlist::class);
        $entityManage=$this->getDoctrine()->getManager();
        $oldPlaylist=$repo->find($id);
        $form=$this->createForm(PlaylistType::class, $oldPlaylist);
        $form->add("Edit", SubmitType::class, [
            'attr' => ['class' => 'btn btn-primary'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            $playlist=$this->getDoctrine()->getRepository(Playlist::class)->findAll();
            return $this->redirectToRoute('backoffice_playlist');        }
        $title = "Update ".$oldPlaylist->getPlaylistName();
        $playlist=$this->getDoctrine()->getRepository(Playlist::class)->findAll();

        return $this->render("back_office/back_office_playlist/update.html.twig", [

            'playlist'=>$playlist,
            'f' =>$form->createView(),
            'page_title' => $title
        ]);

    }


    /**
     *  @param Request $request
     * @return Response
     * @Route ("/filterPlaylists")
     */
    public function FilerPlaylist(Request $request ,PlaylistRepository $PlaylistRepo){
        $user=$this->getUser();
        $response = "";

        $playlists=$PlaylistRepo->findAll();
        foreach($playlists as $playlist) {
            if ($request->get("text") != "") {
                if (stripos($playlist->getPlaylistName() ,$request->get("text")) === false) {

                    unset($playlists[array_search($playlist,$playlists)]);
                } else {

                    $response .= $this->getString($playlist, $user);
                }
            } else {

                $response .= $this->getString($playlist, $user);

            }
        }
        return new Response($response);

    }



    function getString(Playlist $playlist, User $user){

        $s = '<div class="blog_post d-flex flex-md-row flex-column align-items-start justify-content-start">
                            <div class="blog_post_image">
                                <img src="/assets/playlist/images/blog_2.jpg" alt="">
                                <div class="blog_post_date"><a href="#">'.$playlist->getPlaylistCreationDate()->format("d-m-Y").'</a></div>

                            </div>
                            <div class="blog_post_content">
                                <div class="blog_post_title"><a href="#">'.$playlist->getPlaylistName().'</a></div>
                               
                                <div class="blog_post_text">
                                    <p>'.$playlist->getPlaylistDescription().'</p>
                                </div>
                                <div class="blog_post_link"><a href="/UpdatePlaylist/'.$playlist->getId().'">Update this playlist</a></div>
                                <div class="blog_post_link"><a href="/DeletePlaylist/'.$playlist->getId().'">Delete this playlist</a></div>

                            </div>
                        </div>';
        return $s;
    }









    /**
     *  @param Request $request
     * @return Response
     * @Route ("/filterPlaylists1")
     */
    public function FilerPlaylist1(Request $request ,PlaylistRepository $PlaylistRepo){
        $user=$this->getUser();
        $response = "";

        $playlists=$PlaylistRepo->findAll();
        foreach($playlists as $playlist) {
            if ($request->get("text") != "") {
                if (stripos($playlist->getPlaylistName() ,$request->get("text")) === false) {

                    unset($playlists[array_search($playlist,$playlists)]);
                } else {

                    $response .= $this->getString1($playlist, $user);
                }
            } else {

                $response .= $this->getString1($playlist, $user);

            }
        }
        return new Response($response);

    }
    function getString1(Playlist $playlist, User $user){

        $s = '<div class="blog_post d-flex flex-md-row flex-column align-items-start justify-content-start">
                            <div class="blog_post_image">
                                <img src="/assets/playlist/images/blog_2.jpg" alt="">
                                <div class="blog_post_date"><a href="#">'.$playlist->getPlaylistCreationDate()->format("d-m-Y").'</a></div>

                            </div>
                            <div class="blog_post_content">
                                <div class="blog_post_title"><a href="#">'.$playlist->getPlaylistName().'</a></div>
                               
                                <div class="blog_post_text">
                                    <p>'.$playlist->getPlaylistDescription().'</p>
                                </div>
                                

                            </div>
                        </div>';
        return $s;
    }




}
