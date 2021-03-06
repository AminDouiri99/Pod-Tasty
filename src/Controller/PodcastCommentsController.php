<?php

namespace App\Controller;

use App\Entity\PodcastComment;
use App\Entity\Podcast;
use App\Entity\User;
use App\Repository\PodcastCommentRepository;
use App\Repository\PodcastRepository;
use App\Repository\UserRepository;
use DateTime;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PodcastCommentsController extends AbstractController
{
    /**
     * @Route("/podcast/{id}", name="podcast_comments")
     * @param PodcastController $podController
     * @param PodcastCommentRepository $commentsRepo
     * @return Response
     */
    public function index(int $id,PodcastRepository $podcastRepo, PodcastCommentRepository  $commentsRepo): Response
    {

        $getUser = $this->getUser();
        $podcast = $podcastRepo->findOneBy(['id' =>$id]);
        $reviewMoy = 0;
        $userReview = null;
        if (!$podcast->getReviewList()->isEmpty()){
        foreach ($podcast->getReviewList() as $review) {
            $reviewMoy += $review->getRating();
            if($getUser!=null) {
            if($review->getUserId()->getId() == $getUser->getId()) {
                $userReview = $review;
            }
            }
        }
        $reviewMoy /= $podcast->getReviewList()->count();
    }
        $comments=$commentsRepo->findAll();
        return $this->render("default/comments.html.twig", ['comments'=>$comments, 'podcast'=>$podcast,'user'=>$getUser, 'userReview'=>$userReview, "reviewMoy"=>$reviewMoy]);
    }

    /**
     * @Route("/addComment", name="addComment")
     * @param PodcastRepository $podcastRepo
     * @param Request $request
     * @return Response
     */
    function addComment(PodcastRepository $podcastRepo, Request $request) {
        $data = $request->get('comment');
        $comment = new PodcastComment();
        $comment->setCommentText($data);
        $comment->setCommentDate(new DateTime());
        $user=$this->getUser();
        $comment->setUserId($user);
        $podcast=$podcastRepo->findOneBy(['id' =>1]);
        $comment->setPodcastId($podcast);
        $em=$this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();
        $userName=$comment->getUserId()->getUserInfoId()->getUserFirstName()." ".$comment->getUserId()->getUserInfoId()->getUserLastName();
        $commentText=$comment->getCommentText();
        $commentText="'".$comment->getCommentText()."'";
        $editButtons ='';

        if ($this->getUser()->getId() == $comment->getUserId()->getId()) {
            $editButtons =' <div class="comment_tools">
                <div style = "z-index: 99999999999999999;" class="edit" ><i id = "editButton'.$comment->getId().'" onclick = "showUpdateComment(1,'.$comment->getId().')" class="fa fa-edit" ></i ></div >
                <div style = "z-index: 99999999999999999;" class="trash" ><i onclick = "deleteComment('.$comment->getId().')" class="fa fa-trash" ></i ></div >
        </div >';
            }
        return new Response(
            '
            <div id="comment'.$comment->getId().'">
    <div class="user_avatar" >
        <img src="/assets/donut.png">
    </div>
    <div class="comment_toolbar">
        <div class="comment_details">
            <ul>
                <li><span class="user">'.$userName.' </span>
                <span id="deletingMsg'.$comment->getId().'" class="deleteingComment">Deleting comment...</span>

                </li>
                <li style="float:right;margin-right: 10%"><i class="fa fa-calendar"></i>'.$comment->getCommentDate()->format("d M Y").'</li>
            </ul>
        </div>
        '.$editButtons.'

    </div>
    <!-- the comment body -->
       <div class="commentContainer">
                        <div id="commentTextDiv'.$comment->getId() .'" class="commentText">
                            '.$comment->getCommentText() .'
                        </div>
                        <div style="display: none;" id="commentEditText'.$comment->getId() .'">
                        <input class="commentInput editText" onkeypress="checkKeyEdit(event,'.$comment->getId() .')" id="editCommentText'.$comment->getId() .'"  type="text" value="'.$comment->getCommentText() .'" />
                        <span  onclick="showUpdateComment(2, '.$comment->getId() .','.$commentText.')" class="fa fa-close"></span>
                        </div>
                    </div>
    <br><br><br>
</div>');
    }
    /**
     * @Route("/deleteComment", name="")
     * @param int $id
     * @return Response
     */

    function deleteComment(Request $request) {
        $repo=$this->getDoctrine()->getRepository(PodcastComment::class);
        $entityManage=$this->getDoctrine()->getManager();
        $comment=$repo->findOneBy(["id" => $request->get('commentId')]);;
        $entityManage->remove($comment);
        $entityManage->flush();
        return new Response();
    }
    /**
     * @param Request $request
     * @return Response
     * @Route("/UpdateComment")
     */
    function UpdateComment(Request $request) {
        $id = $request->get('commentId');
        $repo=$this->getDoctrine()->getRepository(PodcastComment::class);
        $comment=$repo->findOneBy(["id" => $id]);
        $comment->setCommentText($request->get('commentText'));
        $em=$this->getDoctrine()->getManager();
        $em->flush();
        return new Response();
        }

}