<?php

namespace App\Controller;

use App\Entity\PodcastReview;
use App\Repository\PodcastRepository;
use App\Repository\PodcastReviewRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PodcastReviewController extends AbstractController
{
    /**
     * @Route("/podcast/review", name="podcast_review")
     */
    public function index(): Response
    {
        return $this->render('review.html.twig', [
            'controller_name' => 'PodcastReviewController',
        ]);
    }

    /**
     * @Route("/addReview", name="openReviewBox")
     * @param PodcastReviewRepository $reviewRepo
     * @param UserRepository $userRepo
     * @param PodcastRepository $podcastRepo
     * @param Request $request
     */
    public function addReview(UserRepository $userRepo, PodcastRepository $podcastRepo, Request $request) {
        $data = $request->get('review');
        $review = new PodcastReview();
        $review->setRating($data);
        $user=$userRepo->findOneBy (['id' => 1]);
        $review->setUserId($user);
        $podcast=$podcastRepo->findOneBy (['id' => 1]);
        $review->setPodcastId($podcast);
        $em=$this->getDoctrine()->getManager();
        $em->persist($review);
        $em->flush();
        return $this->redirectToRoute("podcast");
    }
}
