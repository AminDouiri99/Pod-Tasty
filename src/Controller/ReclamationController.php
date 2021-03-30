<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reclamation")
 */
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
     * @Route("/report", name="reclamation_index_admin")
     */
    public function indexAdmin(ReclamationRepository $reclamationRepository): Response
    {
        $repo=$this->getDoctrine()->getRepository(reclamation::class);
        $reclamation=$repo->findAll();
        $user=$this->getUser();
        return $this->render("back_office/podcastBack/reportBack.html.twig", ['user'=>$user,'reclamation'=>$reclamation]);
//        return $this->render("home/home.html.twig", [
//            'reclamations' => $reclamationRepository->findAll(),
//        ]);
    }

    /**
     * @Route("/report/new", name="reclamation_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $user=$this->getUser();
        $reclamation = new Reclamation();
       // $reclamation->setType();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('Home');
        }

        return $this->render('default/comments.html.twig', [
            'reclamation' => $reclamation,
            'f' => $form->createView(),'user'=>$user
        ]);
    }
//    public function setType(string $Type): self
//    {
//        $this->Type = $Type;
//
//        return $this;
//    }

    /**
     * @Route("/{id}", name="reclamation_show", methods={"GET"})
     */
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reclamation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reclamation $reclamation): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reclamation_index');
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'f' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reclamation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Reclamation $reclamation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reclamation_index');
    }

    /**
     * @Route("/{id}", name="report_delete", methods={"DELETE"})
     */
    public function deleteback(Request $request, Reclamation $reclamation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reportBack');
    }
}
