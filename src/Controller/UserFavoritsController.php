<?php

namespace App\Controller;

use App\Entity\PodcastComment;
use App\Repository\PodcastRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserFavoritsController extends AbstractController
{
    /**
     * @Route("/addRemoveFav", name="user_favorits")
     * @param Request $request
     * @param PodcastRepository $podcastRepo
     * @param UserRepository $userRepo
     * @return Response
     */
    public function addRemoveFav(Request $request, PodcastRepository $podcastRepo, UserRepository $userRepo): Response
    {
        $user = $userRepo->find($this->getUser());
        $podcast = $podcastRepo->findOneBy(['id' =>$request->get("id")]);
        $entityManage=$this->getDoctrine()->getManager();
        if($user->getPodcastsFavorite()->contains($podcast)) {
            $user->removePodcastsFavorite($podcast);
            $entityManage->flush();
            return new Response("0");
        } else {
            $user->addPodcastsFavorite($podcast);
            $entityManage->flush();
            return new Response("1");
        }
    }

    /*MOBILE APIS*/
    /**
     * @param $podId
     * @param $userId
     * @param PodcastRepository $podcastRepo
     * @param UserRepository $userRepo
     * @return Response
     * @Route("/mobile/addRmvFav/{podId}/{userId}");
     */
    function addRmvFav($podId, $userId, PodcastRepository $podcastRepo, UserRepository $userRepo): Response
    {
        $user = $userRepo->findOneBy(['id' => $userId]);
        $podcast = $podcastRepo->findOneBy(['id' =>$podId]);
        $entityManage=$this->getDoctrine()->getManager();
        if($user->getPodcastsFavorite()->contains($podcast)) {
            $user->removePodcastsFavorite($podcast);
            $entityManage->flush();
            return new Response(null, Response::HTTP_OK);
        } else {
            $user->addPodcastsFavorite($podcast);
            $entityManage->flush();
            return new Response(null, Response::HTTP_OK);
        }

    }

    /**
     * @param $podId
     * @param $userId
     * @param PodcastRepository $podcastRepo
     * @param UserRepository $userRepo
     * @return Response
     * @Route("/mobile/checkIfFav/{podId}/{userId}");
     */
    function checkFav($podId, $userId, PodcastRepository $podcastRepo, UserRepository $userRepo): Response
    {
        $user = $userRepo->findOneBy(['id' => $userId]);
        $podcast = $podcastRepo->findOneBy(['id' =>$podId]);
        if($user->getPodcastsFavorite()->contains($podcast)) {
            return new Response("1", Response::HTTP_OK);
        } else {
            return new Response("0", Response::HTTP_OK);
        }

    }


    /**
     * @param $podId
     * @param $userId
     * @param PodcastRepository $podcastRepo
     * @param UserRepository $userRepo
     * @return Response
     * @Route("/mobile/getUserFavorites/{userId}");
     */
    function getFavs($userId, PodcastRepository $podcastRepo, UserRepository $userRepo, SerializerInterface $serializer): Response
    {
        $user = $userRepo->findOneBy(["id" =>$userId]);
        $podcasts = $podcastRepo->findAll();
        foreach($podcasts as $pod ) {
            if ($pod->getIsBlocked() == 1 || $pod->getCurrentlyLive() != 0) {
                array_splice($podcasts, array_search($pod ,$podcasts), 1);
            } else {
                if (!$user->getPodcastsFavorite()->indexOf($pod) && $user->getPodcastsFavorite()->indexOf($pod) !== 0) {
                    array_splice($podcasts, array_search($pod ,$podcasts), 1);
                }
            }
            }
        $json = $serializer->serialize($podcasts, 'json',["groups"=>"podcast"]);
        return new Response($json);

    }



}
