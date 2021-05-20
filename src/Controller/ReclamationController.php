<?php

namespace App\Controller;

use App\Entity\Podcast;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\PodcastRepository;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ReclamationController extends AbstractController
{
    /**
     * @Route("/", name="reclamation_index", methods={"GET"})
     */
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render("home/home.html.twig", [
            'reclamation' => $reclamationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/report", name="reclamation_index_admin")
     * @return Response
     */
    public function indexAdmin(): Response
    {
        $repo = $this->getDoctrine()->getRepository(Podcast::class);
        $podcasts = $repo->findAll();
        $user = $this->getUser();
        return $this->render("back_office/reclamationBack/reportBack.html.twig", ['user' => $user, 'podcasts' => $podcasts, 'reclamations' => null, "podcast" => null]);
//        return $this->render("home/home.html.twig", [
//            'reclamations' => $reclamationRepository->findAll(),
//        ]);
    }

    /**
     * @Route("/admin/reports/{id}", name="reportsForPod")
     * @return Response
     */
    public function getReportsForPod($id)
    {
        $repo = $this->getDoctrine()->getRepository(Podcast::class);
        $recRepo = $this->getDoctrine()->getRepository(Reclamation::class);
        $reclamations = $recRepo->findBy(["PodcastId" => $id]);
        $podcast = $repo->find($id);
        $podcasts = $repo->findAll();
        $user = $this->getUser();
        return $this->render("back_office/reclamationBack/reportBack.html.twig", ['user' => $user, 'podcasts' => $podcasts, 'reclamations' => $reclamations, "podcast" => $podcast]);
//        return $this->render("home/home.html.twig", [
//            'reclamations' => $reclamationRepository->findAll(),
//        ]);
    }


    /**
     * @Route("/report/new", name="reclamation_new", methods={"GET","POST"})
     * @param Request $request
     * @param PodcastRepository $podRepo
     * @param UserRepository $userRepo
     * @return Response
     */
    public function new(Request $request, PodcastRepository $podRepo, UserRepository $userRepo): Response
    {
        $u = $this->getUser();
        $user = $userRepo->find($u);
        $podcast = $podRepo->find($request->get("podId"));
        $reclamation = new Reclamation();
        //$reclamation->setStatus(false);
        $reclamation->setType($request->get("type"));
        $reclamation->setStatus(0);

        $reclamation->setUserId($user);
        $reclamation->setPodcastId($podcast);
        $reclamation->setDescription($request->get("desc"));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reclamation);
        $entityManager->flush();
        return new Response();
    }
//    public function setType(string $Type): self
//    {
//        $this->Type = $Type;
//
//        return $this;
//    }


    /**
     * @Route("/deleteReclamation/{id}", name="report_delete")
     */
    public function deleteback(Request $request, Reclamation $reclamation): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($reclamation);
        $entityManager->flush();

        return $this->redirect('/reclamation/report');
    }


    /**
     * @Route("/acceptReclamation/{id}", name="report_accept")
     * @param $id
     * @param Swift_Mailer $mailer
     * @param ReclamationRepository $reclamationRepo
     * @param UserRepository $userRepo
     * @return RedirectResponse
     */
    public function acceptReport($id, Swift_Mailer $mailer, ReclamationRepository $reclamationRepo, UserRepository $userRepo)
    {

        $user = $this->getUser();
        $reclamation = $reclamationRepo->find($id);
        $u = $userRepo->find($user);
        $reclamation->setStatus(1);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('podtastyy@gmail.com')
            ->setTo('amrouchbnr@gmail.com') //$u->getUserEmail()
            ->setBody(
                $this->renderView(
                    'emails/email.html.twig',
                    ['status' => true, 'userName' => $u->getUserInfoId()->getUserFirstName() . ' ' . $u->getUserInfoId()->getUserLastName(), "podcastName" => $reclamation->getPodcastId()->getPodcastName(),]


                ),
                'text/html'
            )
            ->setSubject("Reclamation - PodTasty");
        $mailer->send($message);
        $this->addFlash('message', 'the message has been sent');
        return $this->redirectToRoute('reportsForPod', ["id" => $reclamation->getPodcastId()->getId()]);
    }


    /**
     * @Route("/rejectReclamation/{id}", name="accept_reclamation")
     * @param $id
     * @param $
     * @param Swift_Mailer $mailer
     * @param ReclamationRepository $reclamationRepo
     * @param UserRepository $userRepo
     * @return RedirectResponse
     */
    public function rejectreport($id,Swift_Mailer $mailer, ReclamationRepository $reclamationRepo, UserRepository $userRepo)
    {
        $user = $this->getUser();
        $reclamation = $reclamationRepo->find($id);
        $u = $userRepo->find($user);
        $reclamation->setStatus(-1);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('podtastyy@gmail.com')
            ->setTo($u->getUserEmail())
            ->setBody(
                $this->renderView(
                    'emails/email.html.twig',
                    ["status" => false, 'userName' => $u->getUserInfoId()->getUserFirstName() . ' ' . $u->getUserInfoId()->getUserLastName(), "podcastName" => $reclamation->getPodcastId()->getPodcastName(),]


                ),
                'text/html'
            )
            ->setSubject("Reclamation - PodTasty");
        $mailer->send($message);
        $this->addFlash('message', 'the message has been sent');
        return $this->redirectToRoute('reportsForPod', ["id" => $reclamation->getPodcastId()->getId()]);

    }

    /*MOBILE APIS*/

    /**
     * @Route("/mobile/getReport")
     * @param ReclamationRepository $reportRepo
     * @param SerializerInterface $serializer
     * @return Response
     */
       public function getReport (ReclamationRepository $reportRepo , SerializerInterface $serializer){
           $report = $reportRepo->findAll();
           $json = $serializer->serialize($report, 'json',["groups"=>"reclamations"]);
           return new Response($json);
       }

    /**
     * @Route("/mobile/getReportsByPodcastId/{id}")
     * @param SerializerInterface $serializer
     * @param $id
     * @return Response
     */
    function getRepotsByPodcastId(ReclamationRepository $reportRepository, SerializerInterface $serializer, $id): Response
    {
        $Reports = $reportRepository->findBy(["PodcastId"=>$id]);
        $json = $serializer->serialize($Reports, 'json',["groups"=>'reclamations']);
        return new Response($json);
    }

    /**
     * @Route("/mobile/NewReport", name="reclamation_new", methods={"GET","POST"})
     * @param Request $request
     * @param PodcastRepository $podRepo
     * @param SerializerInterface $serializer
     * @param UserRepository $userRepo
     * @return Response
     */
    public function newReport(Request $request, PodcastRepository $podRepo, SerializerInterface $serializer , UserRepository $userRepo): Response
    {
        $reclamation = new Reclamation();
        //$u = $this->getUser();
        //$user = $userRepo->find($u);
        $user = $userRepo->findOneBy(["id" =>$request->get("userId")]);
        $podcast = $podRepo->findOneBy(["id" =>$request->get("podId")]);
        //$reclamation->setStatus(false);
        $reclamation->setType($request->get("type"));
        $reclamation->setDescription($request->get("description"));
        $reclamation->setStatus(0);

        //$reclamation->setUserId($user);
        $reclamation->setPodcastId($podcast);
        //$reclamation->setDescription($request->get("description"));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reclamation);
        $entityManager->flush();
        //$json = $serializer->serialize($reclamation, 'json',["groups"=>'reclamations']);
        return new Response(null,Response::HTTP_OK);
    }

}


