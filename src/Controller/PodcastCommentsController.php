<?php

namespace App\Controller;

use App\Entity\PodcastComment;
use App\Entity\Podcast;
use App\Entity\User;
use App\Repository\PodcastCommentRepository;
use App\Repository\PodcastRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PodcastCommentsController extends AbstractController
{
    /**
     * @Route("/podcast", name="podcast_comments")
     */
    public function index(PodcastCommentRepository  $commentsRepo): Response
    {
        $comments=$commentsRepo->findAll();
        return $this->render("default/comments.html.twig", ['comments'=>$comments]);
    }

    /**
     * @Route("/addComment", name="addComment")
     * @param PodcastCommentRepository $commentsRepo
     * @param UserRepository $userRepo
     * @param PodcastRepository $podcastRepo
     * @param Request $request
     * @return Response
     */
    function addComment(UserRepository $userRepo, PodcastRepository $podcastRepo,Request $request) {
        $data = $request->get('comment');
        $comment = new PodcastComment();
        $comment->setCommentText($data);
        $comment->setCommentDate(new DateTime());
        $user=$userRepo->findOneBy (['id' => 1]);
        $comment->setUserId($user);
        $podcast=$podcastRepo->findOneBy (['id' => 1]);
        $comment->setPodcastId($podcast);
        $em=$this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();
        $userName=$comment->getUserId()->getUserInfoId()->getUserFirstName()." ".$comment->getUserId()->getUserInfoId()->getUserLastName();
        $commentText=$comment->getCommentText();
        return new Response(
            '
            <div id="comment'.$comment->getId().'">
    <div class="user_avatar" >
        <img src="/assets/donut.png">
    </div>
    <div class="comment_toolbar">
        <div class="comment_details">
            <ul>
                <li><span class="user">'.$userName.' </span></li>
                <li style="float:right;margin-right: 10%"><i class="fa fa-calendar"></i>'.$comment->getCommentDate()->format("d M Y").'</li>
            </ul>
        </div><div class="comment_tools">
                <span id="deletingMsg'.$comment->getId().'" class="deleteingComment">Deleting comment...</span>
                <div class="trash"><i onclick="deleteComment('.$comment->getId().')" class="fa fa-trash"></i></div>
        </div>

    </div>
    <!-- the comment body -->
        <div  class="commentContainer">
            <div class="commentText">
                '.$commentText.'
            </div>
    </div>
    <br><br><br>
</div>');
    }
    /**
     * @Route("/deleteComment/{id}", name="Delete")
     * @param int $id
     * @return RedirectResponse
     */

    function deleteComment(int $id) {
        $repo=$this->getDoctrine()->getRepository(PodcastComment::class);
        $entityManage=$this->getDoctrine()->getManager();
        $comment=$repo->find($id);
        $entityManage->remove($comment);
        $entityManage->flush();
        return $this->redirectToRoute("podcast");
    }

}
