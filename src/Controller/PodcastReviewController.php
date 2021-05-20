<?php

namespace App\Controller;

use App\Entity\Podcast;
use App\Entity\PodcastReview;
use App\Repository\PodcastRepository;
use App\Repository\PodcastReviewRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

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
     * @param Response
     */
    public function addReview(PodcastRepository $podcastRepo, Request $request) {
        $user = $this->getUser();
        $data = $request->get('review');
        $review = new PodcastReview();
        $review->setRating($data);
        $review->setUserId($user);
        $podcast=$podcastRepo->findOneBy (['id' => $request->get("podId")]);
        $review->setPodcastId($podcast);
        $em=$this->getDoctrine()->getManager();
        $em->persist($review);
        $em->flush();
        $reviewMoy = null;
        if (!$podcast->getReviewList()->isEmpty()){
            $reviewMoy = 0;
            foreach ($podcast->getReviewList() as $review) {
                $reviewMoy += $review->getRating();
            }
            $reviewMoy /= $podcast->getReviewList()->count();
        }
        return New Response($review->getId().' '.$reviewMoy);
    }

    /**
     * @Route("/deleteReview/{id}", name="Delete")
     * @param int $id
     * @return Response
     */

    function deleteReview(int $id) {
        $repo=$this->getDoctrine()->getRepository(PodcastReview::class);
        $podcastRepo=$this->getDoctrine()->getRepository(Podcast::class);
        $entityManage=$this->getDoctrine()->getManager();
        $review=$repo->find($id);
        $podcast = $podcastRepo->find($review->getPodcastId()->getId());
        $entityManage->remove($review);
        $entityManage->flush();
        $reviewMoy = null;
        if (!$podcast->getReviewList()->isEmpty()){
            $reviewMoy = 0;
            foreach ($podcast->getReviewList() as $review) {
                $reviewMoy += $review->getRating();
            }
            $reviewMoy /= $podcast->getReviewList()->count();
        }
        return new Response($reviewMoy);
    }



    /*MOBILE APIS*/
    /**
     * @Route("mobile/addReview", name="addReviewMobile")
     * @param SerializerInterface $serializer
     * @param UserRepository $userRepo
     * @param PodcastRepository $podcastRepo
     * @param Request $request
     * @return Response
     */
    public function addReviewMobile(SerializerInterface $serializer, UserRepository $userRepo, PodcastRepository $podcastRepo, Request $request): Response
    {
        $user = $userRepo->findOneBy(["id" =>$request->get("userId")]);
        $data = $request->get('rating');
        $podcast=$podcastRepo->findOneBy (['id' => $request->get("podId")]);
        $review = new PodcastReview();
        $review->setRating(floatval($data));
        $review->setUserId($user);
        $review->setPodcastId($podcast);
        $em=$this->getDoctrine()->getManager();
        $em->persist($review);
        $em->flush();
        $json = $serializer->serialize($review->getId(), 'json');
        return New Response($json, Response::HTTP_OK);
    }


    /**
     * @Route("mobile/delReview/{id}", name="delReviewMobile")
     * @param $id
     * @param PodcastReviewRepository $reviewRepo
     * @return Response
     */
    public function detReviewMobile($id, PodcastReviewRepository $reviewRepo): Response
    {
        $review = $reviewRepo->findOneBy(["id"=>$id]);
        $em=$this->getDoctrine()->getManager();
        $em->remove($review);
        $em->flush();
        return New Response(null, Response::HTTP_OK);
    }

}
