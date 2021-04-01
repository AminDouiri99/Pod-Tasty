<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Entity\Story;
use App\Entity\UserInfo;
use App\Repository\PostRepository;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Request;


class PostController extends AbstractController
{
    /**
     * @Route("/post", name="post")
     */
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        dump($user);
        dump($user->getUserInfoId()->getUserFirstName());

        $post = new Post();

        $form = $this->createFormBuilder($post)
            ->add(
                'text',
                TextareaType::class,
                [
                    'attr' => [
                        'class' => 'textarea bg-transparent has-text-white'

                    ]
                ]
            )
            ->add(
                'postImage',
                FileType::class,
                [
                    'attr' => [
                        'class' => 'file-input bg-transparent',

                    ],
                    'required' => false
                ]
            )
            ->add(
                'privacy',
                CheckboxType::class,
                [
                    'required' => false
                ]
            )
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $post->setUser($user->getUserInfoId())
                ->setCreatedAt(new \DateTime());

            dump($post);



            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $post->getPostImage();


            if ($file) {
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

                dump($this->getParameter('post_images_directory'));

                $file->move(
                    $this->getParameter('post_images_directory'),
                    $fileName
                );

                $post->setPostImage($fileName);
            }




            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
        }

        $repoPosts = $this->getDoctrine()->getRepository(Post::class);
        $posts = $repoPosts->findAll();


        //story Time

        $followings  = $this->getUser()->getUserInfoId()->getFollowing();




        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'posts' => $posts,
            'formPost' => $form->createView(),
            'currentUser' => $user,
            //story variables
            'followings' => $followings
        ]);
    }



    /**
     * @Route("/postDelete/{id}", name="d")
     */
    public function delete($id, PostRepository $repo): Response
    {
        dump("hello");
        $post = $repo->find($id);
        dump($post);
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();


        return $this->redirectToRoute('post');
    }



    //affichage Story



    /**
     * @Route("/story/{id}", name="story1")
     */
    public function ShowStory($id, Request $request): Response
    {
       





        $story = new Story();

        $form = $this->createFormBuilder($story)

            ->add(
                'storyImage',
                FileType::class,
                [
                    'attr' => [
                        'class' => 'file-input',

                    ],
                    'required' => false
                ]
            ) ->add(
                'privacy',
                CheckboxType::class,
                [
                    'required' => false
                ]
            )

            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $story->setOwner($this->getuser()->getUserInfoId());
            



            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $story->getStoryImage();


            if ($file) {
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

                dump($this->getParameter('post_images_directory'));

                $file->move(
                    $this->getParameter('post_images_directory'),
                    $fileName
                );

                $story->setStoryImage($fileName);
            }



            $em = $this->getDoctrine()->getManager();
            $em->persist($story);
            $em->flush();
        }
        $repoUser = $this->getDoctrine()->getRepository(UserInfo::class);
        $user = $repoUser->find($id);
        $stories = $user->getStories();
        foreach($stories as $story1){
            $story1->addView($this->getUser()->getUserInfoId());
            dump($story1);
        }
        
        return $this->render('post/storyView.html.twig', [
            'controller_name' => 'PostController',
            'stories' => $stories,
            'formStory' => $form->createView(),



        ]);
    }



    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
