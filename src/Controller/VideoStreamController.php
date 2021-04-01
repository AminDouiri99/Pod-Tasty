<?php

namespace App\Controller;

use App\Entity\Podcast;
use App\Entity\VideoStream;
use App\Repository\PodcastRepository;
use MongoDB\BSON\Regex;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class VideoStreamController extends AbstractController
{
    /**
     * @Route("/streaming", name="startStreaming")
     * @param Request $request
     * @param PodcastRepository $repo
     * @return Response
     */
    public function index(Request $request, PodcastRepository $repo): Response
    {
        $getUser = $this->getUser();
        $this->container->get('session')->set('deletePod', false);
        $podcast = $repo->findOneBy(["id" =>$this->container->get('session')->get("podId")]);
        $comments = $podcast->getCommentList();
        return $this->render("video_stream/index.html.twig", ['user' => $getUser, "podcast" => $podcast, "comments" => $comments]);

    }


    /**
     * @Route("/stream/{id}", name="podcast_stream")
     * @param Request $request
     * @param PublisherInterface $publisher
     * @return Response
     */
    public function startStreaming(Request $request, PublisherInterface $publisher): Response
    {
        $file = $request->files->get("liveStreaming");
        if ($file != null) {
            $fileName = $request->get("podId") . '.' . uniqid() . '.wav';
            $path = $this->getParameter('PODCAST_FILES') . "/temp" . $request->get("podId") . '/';
            $file->move(
                $path, $fileName
            );
            $fileName = $fileName . "" . $request->get("status");
            $update = new Update("http://127.0.0.1:8000/stream/".$request->get("podId"), $fileName);
                $publisher($update);
        }
        return new Response("");
    }
    /**
     * @Route("/savePodcast", name="savePodcast")
     * @param PodcastRepository $podcastRepo
     * @param Request $request
     * @return Response
     */
    public function savePodcast(PodcastRepository $podcastRepo, Request $request): Response
    {
        $podcast = $podcastRepo->findOneBy(["id"=> $request->get("podId")]);
        $file = $request->files->get("liveStreaming");
        $fileName = uniqid().'.'.$file->guessExtension();
        $file->move(
            $this->getParameter('PODCAST_FILES'),$fileName
        );
        $podcast->setPodcastSource($fileName);
        $podcast->setCurrentlyLive(0);
        $podcast->setIsBlocked(0);
        $em=$this->getDoctrine()->getManager();
        $em->flush();
        $filesystem = new Filesystem();
        $path=$this->getParameter('PODCAST_FILES').'/temp'.$request->get("podId")."/";
        $filesystem->remove($path);
        return new Response("");

    }

    /**
     * @Route("/setPodToLive", name="setPodToLive")
     * @param Request $request
     * @param PodcastRepository $repo
     * @return Response
     */
    public function setPodToLive(Request $request, PodcastRepository $repo): Response
    {
        $entityManage = $this->getDoctrine()->getManager();
        $podcast = $repo->findOneBy(["id" => $request->get("podId")]);
        $podcast->setCurrentlyLive(1);
        $entityManage->flush();
        return new Response("");
    }

    /**
     * @Route("/isItLive/{id}", name="isItLive")
     * @param PodcastRepository $repo
     * @return Response
     */
    public function isItLive($id): Response
    {
        sleep(20);
        if ($this->container->get('session')->get('deletePod') == false){
            $this->container->get('session')->set('deletePod', true);
            if($this->container->get('session')->get('podId') != null) {
                $this->isItLive($this->container->get('session')->get('podId'));
            } else {
                $this->isItLive($id);
            }
        } else {
            $repo = $this->getDoctrine()->getRepository(Podcast::class);
            $em = $this->getDoctrine()->getManager();
            $podcast = $repo->find($id);
            $filesystem = new Filesystem();
            $path=$this->getParameter('PODCAST_FILES') . 'temp'.$podcast->getId()."/";
            $filesystem->remove($path);
            if ($podcast->getPodcastSource() == null) {
                    $em->remove($podcast);
                    $em->flush();
            } else if( $podcast->getCurrentlyLive() != 0){
                $podcast->setCurrentlyLive(0);
                $em->flush();
            }
            $this->container->get('session')->set('podId', null);

        }

        return new Response();
    }

}
