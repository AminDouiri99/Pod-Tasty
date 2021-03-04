<?php

namespace App\Controller;

use App\Entity\Podcast;
use App\Form\PodcastType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PodcastRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormTypeInterface;

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
     * @Route("/AffichPodcast" , name="AffichePodcast")
     */
    public function Affiche(PodcastRepository $repository){
        $repo=$this->getDoctrine()->getRepository(Podcast::class);
        $Podcast=$repo->findAll();
        return $this->render('/podcast/Affiche.html.twig',['podcast'=>$Podcast]);
    }
    /**
     * @Route("/SuppPodcast/{id}" , name="SuppPodcast")
     */
    public function Delete($id, PodcastRepository $repository){
        $Podcast=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($Podcast);
        $em->flush();
        return $this->redirectToRoute("AffichePodcast");
    }
        /**
         * @Route("Podcast/Add")
         */
        function Add(Request $request)
        {
            $Podcast = new Podcast();
            $form = $this->createForm(PodcastType::class, $Podcast);
            $form->add('Ajouter', SubmitType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                /**
                 * @var UploadedFile $file
                 */
                //$file=$Podcast->getPodcastImage();
                $file = $form->get('PodcastImage')->getData();

                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move(
                    $this->getParameter('kernel.project_dir'),$fileName
                );
                $Podcast->setPodcastImage($fileName);
                $em = $this->getDoctrine()->getManager();//->persist($form->getData());

              //  $em=$this->getDoctrine()->getManager()->flush();

                $em->persist($Podcast);
                $em->flush();
                return $this->redirectToRoute('AffichePodcast');
            }
            return $this->render('Podcast/Add.html.twig', [
                'form' => $form->createView()]);
        }

    /**
     * @Route ("Podcast/Update/{id}",name="UpdatePodcast")
     */
        function update(PodcastRepository $repository,$id,Request $request){
            $Podcast=$repository->find($id);
            $form = $this->createForm(PodcastType::class);
            $form->add('Update',SubmitType::class);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                /**
                 * @var UploadedFile $file
                 */
                $file = $form->get('PodcastImage')->getData();
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move(
                    $this->getParameter('kernel.project_dir'),$fileName
                );
                $Podcast->setPodcastImage($fileName);

                $em=$this->getDoctrine()->getManager();
                $em->flush();
                return $this->redirectToRoute('AffichePodcast');
            }
            return $this->render('Podcast/Update.html.twig',[
                'f'=>$form->createView()]);

        }



}
