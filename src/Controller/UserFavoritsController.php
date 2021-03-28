<?php

namespace App\Controller;

use App\Repository\PodcastRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
