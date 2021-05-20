<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TagController extends AbstractController
{
    /**
     * @Route("/tags", name="tag")
     * @param $request
     * @param TagRepository $tagRepository
     * @return Response
     */
    public function index(Request $request,TagRepository $tagRepository): Response
    {
        $tags = $tagRepository->findAll();
        $getUser = $this->getUser();
    $tag = new Tag();
        $tag->setTagStyle("primary");
        $form = $this->createForm(TagType::class, $tag);
        $form->add("Add", SubmitType::class, [
            'attr' => ['class' => 'btn btn-info'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();
            return $this->redirectToRoute("tag");
        }
        return $this->render("back_office/tag/index.html.twig", ['user' => $getUser,'tags' => $tags, 'form' => $form->createView()]);

    }

    /**
     * @Route("/deleteTag/{id}", name="deleteTag")
     * @param $id
     * @param TagRepository $tagRepository
     * @return Response
     */
    public function deleteTag($id, TagRepository $tagRepository): Response
    {
        $tag = $tagRepository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($tag);
        $em->flush();
        return $this->redirectToRoute("tag");

    }

    /**
     * @Route("/updateTag", name="updateTag")
     * @param Request $request
     * @param TagRepository $tagRepository
     */
    public function updateTag(Request $request,TagRepository $tagRepository): Response
    {
        $tag = $tagRepository->find($request->get("id"));
        $tag->setName($request->get("name"));
        $tag->setTagStyle($request->get("style"));
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return new Response();

    }
/* Mobile APIs */

    /**
     * @Route("/mobile/getTags")
     * @param TagRepository $tagRepository
     * @param SerializerInterface $serializer
     * @return Response
     */

    function getTgas(TagRepository $tagRepository, SerializerInterface $serializer){
        $tags = $tagRepository->findAll();
        $json = $serializer->serialize($tags, 'json',["groups"=>"tag"]);
        return new Response($json, Response::HTTP_OK);
    }

}
