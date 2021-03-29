<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/isStillLive", name="isStillLive")
     */
    public function isStillLive(): Response
    {
        $this->container->get('session')->set('deletePod', false);
        return new Response();
    }
}
