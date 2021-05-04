<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\User;
use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
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
     * @Route("/addnotification/{user}", name="add_notification")
     */
    public function addNotif(PublisherInterface $publisher,?User $user){;
        $update= new Update('http://127.0.0.1:8000/addnotification/'.$user->getId());
        $publisher($update);
   /*   $em=$this->getDoctrine()->getManager();
        $em->persist($notif);
        $em->flush();*/
        return new Response("");

    }

    /**
     * @Route("/refreshnotification", name="refresh_notification")
     */

    public function refreshNotif(NotificationRepository $notifRepo, Request $request)
    {

        $notif = $notifRepo->find($request->get('notifId'));
        $response = '<div style="padding:10px;margin:10px" id="notif'.$notif->getId().'">
                    <span style="font-size: 17px;font-weight: bolder">'.$notif->getNotificationTitle().'</span><br>
                    <span style="margin-left: 15px">'.$notif->getNotificationDescription().'</span><br>
                    <span style="float: right">'.$notif->getNotificationDate()->format("D/M/Y").'</span>
                </div>';
        return new Response($response);
    }
}
