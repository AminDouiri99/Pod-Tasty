<?php

namespace App\Controller;

use App\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    /**
     * @Route("/notification", name="notification")
     */
    public function index(): Response
    {
        return $this->render('notification/index.html.twig', [
            'controller_name' => 'NotificationController',
        ]);
    }
    /**
     * @Route("/addnotification", name="add_notification")
     */
    public function addNotif(PublisherInterface $publisher){
        $update= new Update('http://127.0.0.1:8000/notification',[]);
        $publisher($update);
   /*   $em=$this->getDoctrine()->getManager();
        $em->persist($notif);
        $em->flush();*/
        return new Response("");

    }
}
