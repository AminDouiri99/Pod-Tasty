<?php

namespace App\Controller;

use App\Entity\Podcast;
use App\Entity\Tag;
use App\Form\PodcastType;
use App\Repository\TagRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PodcastRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class PodcastController extends AbstractController
{

    /**
     * @Route("", name="Home")
     */
    public function index(): Response
    {

        $repo=$this->getDoctrine()->getRepository(Podcast::class);
        $user=$this->getUser();
        if($user != null){
            if($user->getIsAdmin()){
                return $this->redirectToRoute('back_office');

            }
            if($user->getDesactiveAccount()){
                return $this->render("Home/home.html.twig",['user'=>$user]);
            }

        }
        if($user == new CustomUserMessageAuthenticationException()) {
            $getUser = null;
        }
        $tagRepo = $this->getDoctrine()->getRepository(Tag::class);
        $podcasts = $repo->findAll();
        $tags = $tagRepo->findAll();
        $livePods = [];
        foreach ($podcasts as $pod) {
            if($pod->getIsBlocked() == 1) {
                array_splice($podcasts, array_search($pod, $podcasts), 1);
            } else {
            if ($pod->getCurrentlyLive() != 0) {
                array_splice($podcasts, array_search($pod, $podcasts), 1);
            } if ($pod->getCurrentlyLive() == 1) {
                array_push($livePods, $pod);
            }
            }
        }
        return $this->render("home/Home.html.twig", ['user' => $user, 'podcasts' => $podcasts, "livePods" => $livePods, "tags" => $tags]);
    }


    /**
     * @Route("/podcasts", name="podcast_admin")
     */
    public function indexAdmin(): Response
    {
        $repo=$this->getDoctrine()->getRepository(Podcast::class);
        $podcasts=$repo->findAll();
        $user=$this->getUser();
        return $this->render("back_office/podcastBack/podcast.html.twig", ['user'=>$user,'podcasts'=>$podcasts]);
    }

    /**
     * @Route("/SuppPodcast/{id}" , name="SuppPodcast")
     */
    public function DeleteBack($id, PodcastRepository $repository){
        $Podcast=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($Podcast);
        $em->flush();
        return $this->redirectToRoute("back_office/podcastBack/podcast");
    }

    /**
     * @Route("/DeletePodcast/{id}" , name="SupprPodcast")
     */
    public function Delete($id, PodcastRepository $repository){
        $Podcast=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($Podcast);
        $em->flush();
        return $this->redirectToRoute("AffichePodcast");
    }
    /**
     * @Route("/addViewToPod" , name="addView")
     * @param PodcastRepository $repository
     * @param Request $request
     * @return Response
     */
    public function addViewToPod(PodcastRepository $repository, Request $request)
    {
        $id = $request->get('id');
        $podcast = $repository->find($id);
        $podcast->setPodcastViews($podcast->getPodcastViews() + 1);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return new Response();
    }

    /**
     * @Route("/AddPodcast")
     * @param Request $request
     * @param TagRepository $tagRepo
     * @return RedirectResponse|Response
     */
    function Add(Request $request, TagRepository $tagRepo)
    {
        $tags = $tagRepo->findAll();
        $user = $this->getUser();
        $Podcast = new Podcast();
        $form = $this->createForm(PodcastType::class, $Podcast);
        $form->add("Add", SubmitType::class, [
            'attr' => ['class' => 'btn btn-info'],
        ]);
        //$form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var UploadedFile $file
             */
            //$file=$Podcast->getPodcastImage();
            $podcastsource = $form->get('PodcastSource')->getData();
            if ($podcastsource) {
                $originalFilename = pathinfo($podcastsource->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $newFilename = uniqid() . '.' . $podcastsource->guessExtension();
                try {
                    $podcastsource->move(
                        $this->getParameter('PODCAST_FILES'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $Podcast->setPodcastSource($newFilename);
            }
            $file = $form->get('PodcastImage')->getData();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('PODCAST_FILES'), $fileName
            );
            $tagIds = [];
            $ids = $form->get("tags")->getData();
            $ids = substr($ids, 1, strlen($ids));
            while(strlen($ids) > 0) {
                if(strpos($ids, ",") !== false) {
                $id = substr($ids, 0, strpos($ids, ","));
                $ids = substr($ids, strpos($ids, ",")+1, strlen($ids));
                } else {
                    $id=$ids;
                    $ids = "";
                }
                array_push($tagIds, $id);
            }
            if(count($tags) > 0) {
            foreach($tagIds as $id) {
                $tagtoAdd = $tagRepo->find($id);
                $Podcast->addTagsList($tagtoAdd);
            }
            }
            $Podcast->setPodcastImage($fileName);
            $Podcast->setCommentsAllowed(1);
            $Podcast->setPodcastViews(0);
            $Podcast->setIsBlocked(0);
            $Podcast->setCurrentlyWatching(0);
            $Podcast->setCurrentlyLive(0);
            $em = $this->getDoctrine()->getManager();//->persist($form->getData());

            //  $em=$this->getDoctrine()->getManager()->flush();

            $em->persist($Podcast);
            $em->flush();
            return $this->redirectToRoute('Home');
        }
        return $this->render('Podcast/Add.html.twig', [
            'form' => $form->createView(),'tags'=>$tags, 'user' => $user, 'type'=>"Add podcast"]);
    }

    /**
     * @Route ("PodcastUpdate/{id}",name="UpdatePodcast")
     * @param PodcastRepository $repository
     * @param $id
     * @param Request $request
     * @return RedirectResponse|Response
     */
    function update(PodcastRepository $repository, $id, Request $request)
    {
        $user = $this->getUser();
        $Podcast = $repository->find($id);
        $form = $this->createForm(PodcastType::class);
        $form->add('Update', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var UploadedFile $file
             */
            $file = $form->get('PodcastImage')->getData();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('kernel.project_dir'), $fileName
            );
            $Podcast->setPodcastImage($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('AffichePodcast');
        }
        return $this->render('Podcast/Update.html.twig', [
            'f' => $form->createView(), 'user' => $user]);

    }

    /**
     * @Route("/admin/podcast/changeCommentingStatus/{id}", name="chngComStatus")
     * @param int $id
     */

    function changeCommentingStatus(int $id)
    {
        $repo = $this->getDoctrine()->getRepository(Podcast::class);
        $entityManage = $this->getDoctrine()->getManager();
        $podcast = $repo->findOneBy(["id" => $id]);
        if ($podcast->getCommentsAllowed() == 0)
            $podcast->setCommentsAllowed(1);
        else
            $podcast->setCommentsAllowed(0);
        $entityManage->flush();
        return $this->redirectToRoute("podcast_comments_dashboard", ["id" => $id]);
    }

    /**
     * @Route("/livePodcast", name="atchPod")
     * @param Request $request
     * @return Response
     */
    public function watchPod(Request $request): Response
    {
        {
            $user = $this->getUser();
            $Podcast = new Podcast();
            $form = $this->createForm(PodcastType::class, $Podcast);
            $form->add("Proceed", SubmitType::class, [
                'attr' => ['class' => 'btn btn-info'],
            ]);
            $form->remove("PodcastSource");
            //$form->add('Ajouter', SubmitType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                /**
                 * @var UploadedFile $file
                 */
                //$file=$Podcast->getPodcastImage();
                $file = $form->get('PodcastImage')->getData();
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('PODCAST_FILES'), $fileName
                );
                $Podcast->setPodcastImage($fileName);
                $Podcast->setCurrentlyWatching(0);
                $Podcast->setCommentsAllowed(1);
                $Podcast->setCurrentlyLive(-1);
                $Podcast->setPodcastViews(0);
                $em = $this->getDoctrine()->getManager();//->persist($form->getData());

                //  $em=$this->getDoctrine()->getManager()->flush();

                $em->persist($Podcast);
                $em->flush();
                $this->container->get('session')->set('podId', $Podcast->getId());
                return $this->redirectToRoute('startStreaming');
            }
            return $this->render("podcast/Add.html.twig", ['user' => $user, 'form' => $form->createView(), 'type'=>"Set up your stream"]);

        }

    }

    /**
     * @Route("/removeWatcher" , name="removeWatcher")
     * @param PodcastRepository $repository
     * @param Request $request
     * @param PublisherInterface $publisher
     * @return Response
     */
    public function removeWatcher(PodcastRepository $repository, Request $request, PublisherInterface $publisher)
    {
        $id = $request->get('id');
        $podcast = $repository->find($id);
        if($podcast->getCurrentlyWatching()>0) {
            $podcast->setCurrentlyWatching($podcast->getCurrentlyWatching() - 1);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $update = new Update("http://127.0.0.1:8000/refreshWatchers/".$podcast->getId(), $podcast->getCurrentlyWatching());
            $publisher($update);
        }
        return new Response();
    }

    /**
     * @Route("/addWatcher" , name="addWatcher")
     * @param PodcastRepository $repository
     * @param Request $request
     * @param PublisherInterface $publisher
     * @return Response
     */
    public function addWatcher(PodcastRepository $repository, Request $request, PublisherInterface $publisher)
    {
        $id = $request->get('id');
        $podcast = $repository->find($id);
        $podcast->setPodcastViews($podcast->getPodcastViews() + 1);
        $podcast->setCurrentlyWatching($podcast->getCurrentlyWatching() + 1);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $update = new Update("http://127.0.0.1:8000/refreshWatchers/".$podcast->getId(), $podcast->getCurrentlyWatching());
        $publisher($update);
        return new Response();
    }

    /**
     * @Route("/filterPodcasts" , name="filterPodcasts")
     * @param TagRepository $tagRepo
     * @param PodcastRepository $repository
     * @param Request $request
     * @return Response
     */
    public function filterPodcasts(TagRepository $tagRepo,PodcastRepository $repository, Request $request)
    {

        $id = $request->get('id');
        if($id!=0) {
            $tag = $tagRepo->find($id);
            if ($tag->getPodcastsList()->count() === 0) {
                $response = "Sorry we are out of tasty podcasts !";
                return new Response($response);
            }
        }else {
            $tag = null;
        }

        $podcasts = $repository->findAll();
        $livePods = [];
        $status =0;
        foreach ($podcasts as $pod) {
            if ($tag != null) {
                if(!($tag->getPodcastsList()->indexOf($pod) > -1) || $pod->getCurrentlyLive() != 0){
                    array_splice($podcasts, array_search($pod, $podcasts), 1);
                }
            }
                if( $pod->getCurrentlyLive() == 1) {
                    $addIt = true;
                    if($tag != null) {
                        if(!($tag->getPodcastsList()->indexOf($pod) > -1)){
                            $addIt = false;
                        }
                    }
                    if($addIt) {
                    array_push($livePods, $pod);
                    }
                }
            }
        $noPods = true;
        $response = "";
        if(count($livePods) > 0) {
            $noPods = false;
            $response ='<div class="wrapper">
            <h2>Currently Live <img src="/assets/streaming/live.png" style="margin-left: 20px" height="35" width="35"/></h2>

            <div class="cards">';
            $response .= $this->returnResponse($livePods);
            $response.="
            </div>
        </div>";

        }
        if(count($podcasts) > 0) {
            $noPods = false;
            $response .= '    <div class="wrapper">
        <h2>All podcasts</h2>

        <div class="cards">';
        $response .= $this->returnResponse($podcasts);
        $response.="
            </div>
        </div>";
        }
        if($noPods) {
            $response = "Sorry we are out of tasty podcasts !";
        }
        return new Response($response);

    }
    function returnResponse($podcasts): string
    {
        $data = "";
            foreach($podcasts as $pod) {
            $data.='<figure style="margin-left: 0px" class="card">
                    <div class="show" style="width: 100%">
                        <div class="show_image">
                             <div  class="podcastViews">'.$pod->getPodcastViews().' Views</div>
                            <a href="/podcast/'.$pod->getId().'"><img class="podImg" src="';
                            if ($pod->getPodcastImage() != null) {

                                $data.='Files/podcastFiles/'.$pod->getPodcastImage();
                            } else {
                               $data.= 'assets/home/defaultPod.png';
                            }
                $data.=  '">
                                <div class="show_play_icon">
                                        <img src="assets/frontOffice/images/play.png">

                                </div>
                                <div class="show_title_2">'.$pod->getPodcastName().'</div>
                            </a>
                            <div class="show_tags">
                                <div class="tags">
                                    <ul class="flex-row align-items-start justify-content-start">';
                         foreach($pod->getTagsList() as $tag){
                                        $data.='<li style="margin-bottom: 10px !important;;background-color: transparent"><button style="pointer-events:none;border-radius: 10px" class="badge-pill btn-'.$tag->getTagStyle().'" id="tag'.$tag->getId().'">'.$tag->getName().'</button></li>';
                                       }
                                    $data.='</ul>
                                </div>
                            </div>
                        </div>
                    </div>
            </figure>
';
            }
        return $data;
    }

    /**
     * @Route("/podcastBlock/{id}", name="updateBlockStatus")
     * @param $id
     */
    public function changeBlockStatus($id){
        $repo = $this->getDoctrine()->getRepository(Podcast::class);
        $podcast = $repo->find($id);
        if($podcast->getIsBlocked() == 1){
            $podcast->setIsBlocked(0);
        } else {

            $podcast->setIsBlocked(1);
        }
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute("reportsForPod",["id"=> $id]);
    }

}
