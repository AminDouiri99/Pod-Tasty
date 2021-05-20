<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\Podcast;
use App\Entity\Tag;
use App\Entity\User;
use App\Form\PodcastType;
use App\Repository\ChannelRepository;
use App\Repository\PlaylistRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
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
// use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class PodcastController extends AbstractController
{

    /**
     * @Route("", name="Home")
     */
    public function index(): Response
    {
        $repo=$this->getDoctrine()->getRepository(Podcast::class);
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

        $user=$this->getUser();
        if($user != null){
            if($user->getIsAdmin()){
                return $this->redirectToRoute('back_office');

            }
            if($user->getDesactiveAccount()){
                return $this->render("Home/home.html.twig",['podcasts' => $podcasts, "livePods" => $livePods, "tags" => $tags, 'user' => $user]);
            }

        }
        if($user == new CustomUserMessageAuthenticationException()) {
            $getUser = null;
        }
        return $this->render("home/home.html.twig", ['podcasts' => $podcasts, "livePods" => $livePods, "tags" => $tags, 'user' => $user]);
    }


    /**
     * @Route("/admin/podcasts", name="podcast_admin")
     */
    public function indexAdmin(): Response
    {
        $repo=$this->getDoctrine()->getRepository(Podcast::class);
        $podcasts=$repo->findAll();
        $user=$this->getUser();
        return $this->render("back_office/podcastBack/podcast.html.twig", ['user'=>$user,'podcasts'=>$podcasts]);
    }

//    /**
//     * @Route("/Mobile/getPodcasts", name="liste")
//     */
//        public function getPodcasts(PodcastRepository $repo , SerializerInterface $serializerInterface)
//        {
//            $podcasts = $repo->findAll();
//            $json = $serializerInterface->serialize($podcasts, 'json',["groups"=>'podcasts']);
//
//            dump($json);
//        }

//    /**
//     * @Route ("/pod",name="pod"
//     * @param Request $request
//     * @param SerializerInterface $serializer
//     * @param EntityManagerInterface $em
//     * @return Response
//     */
//        public function addPodcast(Request $request, SerializerInterface $serializer, EntityManagerInterface $em){
//            $content=$request->getContent();
//            $data=$serializer->deserializa($content,Podcast::class,'json');
//            $em->persist($data);
//            $em->flush();
//            return new Response('podcast added successfully');
//        }

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
     * @param UserRepository $userRepo
     * @param ChannelRepository $channelRepo
     * @param Request $request
     * @param TagRepository $tagRepo
     * @return RedirectResponse|Response
     */
    function Add(UserRepository $userRepo, PlaylistRepository $playlistRepo,Request $request, TagRepository $tagRepo)
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
                $this->getParameter("PODCAST_FILES"), $fileName
            );
            $tagIds = $this->podcastTags($form->get("tags")->getData());

            if(count($tagIds) > 0) {
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

    function podcastTags($ids) {
        $tagIds = [];
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
        return $tagIds;
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
     * @param UserRepository $userRepo
     * @param Request $request
     * @param PublisherInterface $publisher
     * @return Response
     */
    public function watchPod(UserRepository $userRepo,Request $request, PublisherInterface $publisher): Response
    {
        {
            $user = $this->getUser();
            $Podcast = new Podcast();
            $tagRepo = $this->getDoctrine()->getRepository(Tag::class);
            $tags = $tagRepo->findAll();

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
                $tagIds = $this->podcastTags($form->get("tags")->getData());
                if(count($tagIds) > 0) {
                    foreach($tagIds as $id) {
                        $tagtoAdd = $tagRepo->find($id);
                        $Podcast->addTagsList($tagtoAdd);
                    }
                }
                $Podcast->setPodcastImage($fileName);
                $Podcast->setCurrentlyWatching(0);
                $Podcast->setCommentsAllowed(1);
                $Podcast->setCurrentlyLive(-1);
                $Podcast->setPodcastViews(0);
                $Podcast->setIsBlocked(0);
                $em = $this->getDoctrine()->getManager();//->persist($form->getData());

                //  $em=$this->getDoctrine()->getManager()->flush();

                $em->persist($Podcast);
                $em->flush();
                $this->container->get('session')->set('podId', $Podcast->getId());

                $user = $userRepo->find($user->getId());
                $title="Live podcast";
                $desc=$user->getUserInfoId()->getUserFirstName()." ".$this->getUser()->getUserInfoId()->getUserLastName() ."Started a <a href='/podcast/".$Podcast->getId()."'>live podcast</a>";
                $notif=new Notification();
                $notif->setNotificationTitle($title);
                $notif->setIsViewed(false);
                $notif->setNotificationDescription($desc);
                $notif->setNotificationDate(new \DateTime('now'));
                foreach($user->getUserInfoId()->getFollowers() as $follower) {
                    $user = $userRepo->find($follower->getId());
                    $notif->setUserId($user);
                    $em->persist($notif);
                    $em->flush();
                    $update= new Update('http://127.0.0.1:8000/addnotification/'.$follower->getId(),$notif->getId());
                    $publisher($update);
                }
                $em->flush();

                return $this->redirectToRoute('startStreaming');
            }
            return $this->render("podcast/Add.html.twig", ['user' => $user, 'form' => $form->createView(), 'type'=>"Set up your stream", "tags"=>$tags]);

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



    /**
     * @Route("/AddPodcastFromJava" )
     */
    function AddP(Request $request)
    {
        $user = $this->getUser();
        $Podcast = new Podcast();
        $form = $this->createForm(PodcastType::class, $Podcast);
        $form->add("Add", SubmitType::class, [
            'attr' => ['class' => 'btn btn-info'],
        ]);
        //$form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);






        //$podcastsource = ($request->files->get("PodcastSource"));
        /*        if ($podcastsource) {
                   $originalFilename = pathinfo($podcastsource->getClientOriginalName(), PATHINFO_FILENAME);
                   // this is needed to safely include the file name as part of the URL
                   $newFilename = uniqid() . '.' . $podcastsource->guessExtension();
                   try {
                       $podcastsource->move(
                           $this->getParameter('PODCAST_FILES')."/Files/podcastFiles/",
                           $newFilename
                       );
                   } catch (FileException $e) {
                       dd($e->getMessage());
                       exit;
                   }

                   $Podcast->setPodcastSource($newFilename);
               } */

        $fileaudio = ($request->files->get("PodcastSource"));
        $fileName1 = md5(uniqid()) . '.' . $fileaudio->guessExtension();
        $fileaudio->move(
            $this->getParameter('PODCAST_FILES')."/public/posts", $fileName1
        );

        $file = ($request->files->get("PodcastImage"));
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move(
            $this->getParameter('PODCAST_FILES')."/public/posts", $fileName
        );



        $Podcast->setPodcastImage($fileName);
        $Podcast->setCommentsAllowed(1);
        $Podcast->setPodcastViews(0);
        $Podcast->setCurrentlyWatching(0);
        $Podcast->setCurrentlyLive(0);
        $em = $this->getDoctrine()->getManager();//->persist($form->getData());

        //  $em=$this->getDoctrine()->getManager()->flush();

        /*$em->persist($Podcast);
        $em->flush();*/
        return new Response(json_encode(array("image"=>$fileName,"audio"=>$fileName1),Response::HTTP_OK));
    }


/*MOBILE APIS*/

    /**
     * @Route("/mobile/getPodcast")
     * @param PodcastRepository $podcastRepository
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @return Response
     */

    function getPodcast(PodcastRepository $podcastRepository, SerializerInterface $serializer){
        $podcasts = $podcastRepository->findAll();
        $json = $serializer->serialize($podcasts, 'json',["groups"=>"podcast"]);
        return new Response($json);
    }

    /**
     * @Route("mobile/getPodcastById" )
     */
    function getPodcastByIdForMobile(PodcastRepository  $podcastRepository,Request $request)
    {
        $podcast = $podcastRepository->findOneBy(["id"=>$request->get("id")]);
        return new Response(json_encode(array("Podcast"=>$podcast),Response::HTTP_OK));
    }





    /**
     * @Route("mobile/AddPodcast")
     * @param UserRepository $userRepo
     * @param Request $request
     * @return Response
     */

    function AjoutPodcastMobile(PodcastRepository $podcastRepo , UserRepository $userRepo, Request $request, SerializerInterface $serializer):Response
    {

        $Podcast = new Podcast();
        $Podcast->setPodcastName($request->get("PodcastName"));
        $Podcast->setPodcastDescription($request->get("PodcastDescription"));
        $Podcast->setPodcastDate(new DateTime());

        /*$file = ($request->files->get("PodcastImage"));
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move(
            $this->getParameter('PODCAST_FILES')."/public/posts", $fileName);
        $Podcast->setPodcastImage($fileName);

        $fileaudio = ($request->files->get("PodcastSource"));
        $fileName1 = md5(uniqid()) . '.' . $fileaudio->guessExtension();
        $fileaudio->move(
            $this->getParameter('PODCAST_FILES')."/public/posts", $fileName1
        );
        $Podcast->setPodcastSource($fileName1);*/

        $Podcast->setPodcastImage($request->get("podcastImage"));
        $Podcast->setPodcastSource($request->get("podcastSource"));
        $Podcast->setCommentsAllowed(1);
        $Podcast->setPodcastViews(0);
        $Podcast->setIsBlocked(0);
        $Podcast->setCurrentlyWatching(0);
        $Podcast->setCurrentlyLive(0);
        $em = $this->getDoctrine()->getManager();
        $em->persist($Podcast);
        $em->flush();
        //$json = $serializer->serialize($Podcast, 'json',["groups"=>'Podcast']);
        return new Response(null,Response::HTTP_OK);


           /* $podcastsource = $form->get('PodcastSource')->getData();
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
                $this->getParameter("PODCAST_FILES"), $fileName
            );
//            $tagIds = $this->podcastTags($form->get("tags")->getData());
//
//            if(count($tagIds) > 0) {
//                foreach($tagIds as $id) {
//                    $tagtoAdd = $tagRepo->find($id);
//                    $Podcast->addTagsList($tagtoAdd);
//                }
//            }*/

        }

    /**
     * @Route("mobile/insertimg" )
     */
    function podcastimg(Request $request){
        $podcastimage = $request->files->get("myimg");


        if (empty($podcastimage)) {
            return new Response("No img specified",
                Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
        }
        if ($podcastimage) {
            $originalFilename = pathinfo($podcastimage->getFileName
            (), PATHINFO_FILENAME);
           // $id=intval($request->get("id"));
 //           $this->getDoctrine()->getRepository(Podcast::class)->find($id)->setPodcastImage($originalFilename  . '.' . $podcastimage->guessExtension());
   //         $em=$this->getDoctrine()->getManager()->flush();
            $newFilename = $originalFilename.uniqid() . '.' . $podcastimage->guessExtension();
            try {
                $podcastimage->move(
                    $this->getParameter('PODCAST_FILES'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            return new Response($newFilename,
                Response::HTTP_OK, ['content-type' => 'text/plain']);
        }


    }

    /**
     * @Route("mobile/insertaudio" )
     */
    function podcastaudio(Request $request , SerializerInterface $serializer){

        $podcastaudio = $request->files->get("myaudio");
        $ext = $request->get("extension");


             if (empty($podcastaudio)) {
            return new Response("No audio specified",
                Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
        }
            if($podcastaudio) {
                $originalFilename = $podcastaudio->getFileName
                (); //PATHINFO_FILENAME);
                // $id=intval($request->get("id"));
                //$this->getDoctrine()->getRepository(Podcast::class)->find($id)->setPodcastSource($originalFilename  . '.' . $podcastaudio->guessExtension());
                //$em=$this->getDoctrine()->getManager()->flush();
                $newFilename = $originalFilename.uniqid().'.'.$ext; //. '.' . $podcastaudio->guessExtension();
                try {
                    $podcastaudio->move(
                        $this->getParameter('PODCAST_FILES'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $json = $serializer->serialize($newFilename, 'json', ["groups" => 'Podcast']);
                 return new Response($json, Response::HTTP_OK);
            }
            //return new Response($newFilename,
              //  Response::HTTP_OK, ['content-type' => 'text/plain']);


    }

    /**
     * @Route("mobile/DeletePodcast/{id}" , name="SupprPodcast")
     * @param $id
     * @return Response
     */
    public function DeletePodcastMobile($id): Response
    {
        $repo=$this->getDoctrine()->getRepository(Podcast::class);
        $Podcast=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($Podcast);
        $em->flush();
        return new Response(null,Response::HTTP_OK);
    }

    /**
     * @Route ("PodcastUpdate/{id}",name="UpdatePodcast")
     * @param PodcastRepository $repository
     * @param $id
     * @param Request $request
     * @return RedirectResponse|Response
     */
    function updatePodcastMobile(PodcastRepository $repository, $id, Request $request)
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
            return new Response(null,Response::HTTP_OK);

        }

    }

}
