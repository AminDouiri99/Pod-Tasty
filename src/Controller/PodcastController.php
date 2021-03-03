<?php

namespace App\Controller;

use App\Entity\Podcast;
use App\Form\PodcastFormType;
use App\Repository\PodcastRepository;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PodcastController extends AbstractController
{
    /**
     * @Route("/podcast", name="podcast")
     */
    public function index(): Response
    {
        return $this->render('podcast/index.html.twig', [
            'controller_name' => 'PodcastController',
        ]);
    }

    /**
     * @param PodcastRepository $repository
     * @return Response
     * @Route("/AffichPodcast",name="AffichePodcast")
     */
    public function Affiche(PodcastRepository $repository){
      $repo=$this->getDoctrine()->getRepository(Podcast::class);
      $Podcast=$repo->findAll();
      return $this->render('/podcast/Affiche.html.twig',['podcast'=>$Podcast]);
    }

    /**
     * @Route("SuppPodcast")
     * @param $id
     */
    public function Delete($id, PodcastRepository $repository){
        $Podcast=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($Podcast);
        $em->flush();
        return $this->redirectToRoute("Affiche.html.twig");
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     * @Route("/AddPodcast")
     */
     function add(\Symfony\Component\HttpFoundation\Request $request){
        $Podcast= new Podcast();
        $form=$this->createForm(PodcastFormType::class,$Podcast);
         $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($Podcast);
            $em->flush();
            //return $this->redirectToRoute('AffichePodcast');
        }
        return $this->render('Podcast/Add.html.twig',[
            'form'=>$form->createView()]);
     }
     /**
      * @Route ("Podcast/Update/{id}",name="UpdatePodcast"
      */
     /*function update(PodcastRepository $repository, $id,\Symfony\Component\HttpFoundation\Request $request){
         $Podcast=$repository->find($id);
         $form=$this->createForm(PodcastFormType::class,$Podcast);
         $form->add('Update',SubmitType::class);
         $form->handleRequest($request);
         if($form->isSubmitted() && $form->isValid()) {
             $em = $this->getDoctrine()->getManager();
             $em->flush();
             return $this->redirectToRoute('AffichePodcast');
         }
         return $this->render('Podcast/Update.html.twig',[
             'form'=>$form->createView()]);


     }
*/
}
