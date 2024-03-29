<?php

namespace App\Controller;
use App\Entity\PodcastComment;
use App\Entity\User;
use App\Repository\PodcastCommentRepository;
use App\Repository\PodcastRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PodcastCommentsController extends AbstractController
{

    /**
     * @Route("/podcast/{id}", name="podcast_comments")
     * @param int $id
     * @param PodcastRepository $podcastRepo
     * @param UserRepository $userRepo
     * @return Response
     */
    public function index(int $id,PodcastRepository $podcastRepo, UserRepository $userRepo): Response
    {

        $isFavourite = false;
        $podcast = $podcastRepo->findOneBy(['id' =>$id]);
        $getUser = $this->getUser();
        if($getUser != null) {
        $getUser = $userRepo->find($this->getUser());
            if($getUser->getPodcastsFavorite()->contains($podcast)) {
                $isFavourite = true;
            }
        }
        $reviewMoy = null;
        $userReview = null;
        if (!$podcast->getReviewList()->isEmpty()){
            $reviewMoy = 0;
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
        $comments=$podcast->getCommentList();
        return $this->render("default/comments.html.twig", ['comments'=>$comments, 'podcast'=>$podcast,'user'=>$getUser, 'userReview'=>$userReview, "reviewMoy"=>$reviewMoy, "isFavourite"=>$isFavourite]);
    }

    /**

     * @Route("/adminPodcast/{id}", name="podcast_comments_dashboard")
     * @param int $id
     * @param PodcastRepository $podcastRepo
     * @return Response
     */
    public function loadCommentsForDashboard(int $id,PodcastRepository $podcastRepo): Response
    {

        $getUser = $this->getUser();
        $podcast = $podcastRepo->findOneBy(['id' =>$id]);
        $reviewMoy = null;
        if (!$podcast->getReviewList()->isEmpty()){
            $reviewMoy = 0;
            foreach ($podcast->getReviewList() as $review) {
                $reviewMoy += $review->getRating();
                if($getUser!=null) {
                }
            }
            $reviewMoy /= $podcast->getReviewList()->count();
        }
        $comments=$podcast->getCommentList();

        return $this->render("back_office/commentsBack/commentBack.html.twig", ['comments'=>$comments, 'podcast'=>$podcast,'user'=>$getUser, "reviewMoy"=>$reviewMoy]);
    }

    /**
     * @Route("/addComment", name="addComment")
     * @param PodcastRepository $podcastRepo
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param PublisherInterface $publisher
     * @return Response
     */
    function addComment(PodcastRepository $podcastRepo, Request $request, ValidatorInterface $validator,PublisherInterface $publisher): Response
    {

        $comment = new PodcastComment();
        $data = $request->get('comment');
        $comment->setCommentText($data);
        $commentErrors = "";
        $commentErrors=$validator->validate($comment);
        if ($commentErrors !="") {
        return new Response("1");
        } else {
            $podcast = $podcastRepo->findOneBy(['id' => $request->get('podId')]);
            if($podcast->getCommentsAllowed() == 0) {
                return new Response("0");
            }
            $comment->setCommentDate(new DateTime());
            $user = $this->getUser();
            $comment->setUserId($user);
            $comment->setPodcastId($podcast);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $update = new Update("http://127.0.0.1:8000/addComment", $comment->getId());
            $publisher($update);
            return new Response("");
        }
    }

    /**
     * @Route ("/refreshCommentsList", name="refreshCommentsList")
     * @param Request $request
     * @param PodcastCommentsController $comController
     * @return Response
     */
    function refreshCommentsList(Request $request, PodcastCommentRepository $commentsRepo): Response
    {
        $comment = $commentsRepo->findOneBy(['id'=>$request->get('comId')]);
        if ($comment->getPodcastId()->getId() == $request->get('podId')) {
            $userName = $comment->getUserId()->getUserInfoId()->getUserFirstName() . " " . $comment->getUserId()->getUserInfoId()->getUserLastName();
            $commentText = $comment->getCommentText();
            $commentText = "'" . $comment->getCommentText() . "'";
            $editButtons = '';
            $currentRoute = $request->get("currentR");
            $streaming = false;
            if(strpos($currentRoute, "streaming"))
            {
                $streaming = true;
            }

            if($this->getUser() != null) {
                if ($this->getUser()->getId() == $comment->getUserId()->getId() || $streaming) {
                    $editButtons = ' <div class="comment_tools">';
                    if (!$streaming) {
                        $editButtons = $editButtons . '<div style = "z-index: 99999999999999999;" class="edit" ><i id = "editButton' . $comment->getId() . '" onclick = "showUpdateComment(1,' . $comment->getId() . ')" class="fa fa-edit" ></i ></div >';
                    }
                    $editButtons = $editButtons . '<div style = "z-index: 99999999999999999;" class="trash" ><i onclick = "deleteComment(' . $comment->getId() . ')" class="fa fa-trash" ></i ></div >
        </div >';
                }
            }
            $response = '
            <div id="comment' . $comment->getId() . '">
    <div class="user_avatar" >
        <img src="/assets/donut.png">
    </div>
    <div class="comment_toolbar">
        <div class="comment_details">
            <ul>
                <li><span class="user">' . $userName . ' </span>';

            if (!$streaming) {
                $response=$response. '<span id="deletingMsg' . $comment->getId() . '" class="deleteingComment">Deleting comment...</span>';
            }
            $response=$response. '</li>';
            if (!$streaming) {
                $response=$response. '<li style="float:right;margin-right: 10%"><i class="fa fa-calendar"></i>' . $comment->getCommentDate()->format("d M Y") . '</li>';
            }
            $response=$response.  '</ul>
        </div>
        ' . $editButtons . '

    </div>
    <!-- the comment body -->
       <div class="commentContainer">
                        <div id="commentTextDiv' . $comment->getId() . '" class="commentText">
                            ' . $comment->getCommentText() . '
                        </div>';
            if (!$streaming) {
                $response=$response. '<div style="display: none;" id="commentEditText' . $comment->getId() . '">
                        <input class="commentInput editText" onkeypress="checkKeyEdit(event,' . $comment->getId() . ')" id="editCommentText' . $comment->getId() . '"  type="text" value="' . $comment->getCommentText() . '" />
                        <span  onclick="showUpdateComment(2, ' . $comment->getId() . ',' . $commentText . ')" class="fa fa-close"></span>
                        </div>';
            } else {
                $response=$response. '<div style="height: 10px;margin-bottom: -40px">
                                    <span id="deletingMsg'.$comment->getId().'" style="width: 200px;margin-right: -80px" class="deleteingComment">Deleting comment...</span>
                                    </div>';
            }
            $response=$response. '</div>
    <br><br><br>
</div>';
        } else {
            $response = "-1";
        }
        return new Response($response);
    }
    /**
     * @Route("/deleteComment", name="deleteComment")
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
     * @param ValidatorInterface $validator
     * @return Response
     * @Route("/UpdateComment");
     */
    function UpdateComment(Request $request) {

        $id = $request->get('commentId');
        $repo=$this->getDoctrine()->getRepository(PodcastComment::class);
        $comment=$repo->findOneBy(["id" => $id]);
        $comment->setCommentText($request->get('commentText'));
        $em=$this->getDoctrine()->getManager();
        $em->flush();
        return new Response(1);

        }

    /**
     * @param Request $request
     * @param PodcastRepository $podcastRepo
     * @return Response
     * @Route("/filterComments");
     */
    function fiilterComments(Request $request, PodcastRepository $podcastRepo ) {

        $podcast = $podcastRepo->findOneBy(["id"=>$request->get("id")]);
        $comments=$podcast->getCommentList();
        $response = "";
        foreach($comments as $comment) {
            if ($request->get("text") != "") {
                if (stripos($comment->getCommentText() ,$request->get("text")) === false) {
                    $comments->removeElement($comment);
                } else {
                    $response .= $this->getString($comment);
                }
            } else {
                $response .= $this->getString($comment);
            }
        }
        return new Response($response);

    }

    public function getString($comment): string
    {
       $res = '<div id="comment' . $comment->getId() . '">
                    <!-- current #{user} avatar -->
                    <div class="user_avatar">
                        <img src="/assets/donut.png">
                    </div>
                    <div class="comment_toolbar">

                        <!-- inc. date and time -->
                        <div class="comment_details">
                            <ul>
                                <li style="width: 100%"><span class="user"> ' . $comment->getUserId()->getUserInfoId()->getUserFirstName() . ' ' . $comment->getUserId()->getUserInfoId()->getUserLastName() . '</span>
                                    <span id="deletingMsg' . $comment->getId() . '" class="deleteingComment">Deleting comment...</span>

                                </li>
                <li style="float:right;margin-right: 10%"><i class="fa fa-calendar"></i>' . $comment->getCommentDate()->format("d M Y") . '</li>
                            </ul>
                        </div>';
        if ($this->getUser() != null) {
            $userRepo =$this->getDoctrine()->getRepository(User::class);
            $user = $userRepo->find($this->getUser());
            if ($comment->getUserId() ==$user) {
                $res .= '
                            <div class="comment_tools">
                                <div style="z-index: 99999999999999999"  class="edit"><i id="editButton' . $comment->getId() . '" onclick="showUpdateComment(1, ' . $comment->getId() . ')" class="fa fa-edit"></i></div>
                                <div style="z-index: 99999999999999999" class="trash"><i onclick="deleteComment(' . $comment->getId() . ')" class="fa fa-trash"></i></div>
                            </div>';
            }
        }
        $commentText = $comment->getCommentText();
        $commentText = "'" . $comment->getCommentText() . "'";
        $res .= '</div>
                <!-- the comment body -->
                <div    class="commentContainer">
                    <div id="commentTextDiv' . $comment->getId() . '" class="commentText">
                        ' . $comment->getCommentText() . '
                    </div>
                    <div style="display: none;height: 100%" id="commentEditText{{ comment.id }}">
                    <input class="commentInput editText" onkeypress="checkKeyEdit(event,' . $comment->getId() . ')" id="editCommentText' . $comment->getId() . '"  type="text" value="' . $comment->getCommentText() . '" />
                    <span title="cancel" onclick="showUpdateComment(2, ' . $comment->getId() . ',' . $commentText . ')" class="fa fa-close"></span>
                    </div>
                </div>
                <br><br><br>    
                </div>
';
        return $res;
    }

}