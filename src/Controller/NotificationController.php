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

        $notif = $notifRepo->findOneBy(['id' => $request->get('notifId')]);
        $notifnotfiwed = 0;
        if ($notif->getUserId()->getId() == $this->getUser()->getId()) {
            $allNotif = $this->getUser()->getNotificationList();

            foreach ($allNotif as $n) {
                if ($n . isViewed == false) {
                    $notifnotfiwed++;
                }
            }
        }
        return new Response($notifnotfiwed);
    }
}
