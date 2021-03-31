<?php

namespace App\Controller;

use App\Entity\Story;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StoryController extends AbstractController
{
    /**
     * @Route("/story", name="story")
     */
    public function index(): Response
    {

        $repo = $this->getDoctrine()->getRepository(Story::class);
        $stories = $repo->findAll();


        return $this->render('story/index.html.twig', [
            'stories' => $stories,
        ]);
    }
}
