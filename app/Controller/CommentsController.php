<?php

namespace App\Controller;

use App\Models\Answers;
use App\Models\Comments;
use App\Models\Likes;
use App\Models\Messages;
use App\Models\Publications;
use App\Models\UserInformation;
use App\Models\Users;

class CommentsController extends Controller
{
    public function index()
    {
        $user_id = self::$authObj->getUserId();
        $this->render([
            "vue" => "comments",
            "title" => "COMMENTS",
            "user" => Users::getUser($user_id),
            "cptMessages" => Messages::countUnreadMessages($user_id),
            "userInfos" => UserInformation::getUserMoreInformation($user_id),
            "publicationList" => []
        ]);
    }

    public function getCommentsAndAnswers($publication_id)
    {
        $user_id = self::$authObj->getUserId();
        $commentTable = [];
        // publication
        $publication = Publications::getPublicationById($publication_id);
        $publication->dislikes = Likes::getDislikes($publication->publication_id);
        $publication->likes = Likes::getLikes($publication->publication_id);
        $publication->my_like = Likes::getLikesOfUser($publication->publication_id, $user_id);
        if ($publication->my_like) {
            if (strcmp($publication->my_like->like_value, "1") == 0) {
                $publication->strLike = "You and " . ($publication->likes->_like - 1) . " person(s) likes";
            } else {
                $publication->strLike = $publication->likes->_like . " persons likes";
            }
        }else{
            $publication->strLike = $publication->likes->_like . " persons likes";
        }
        $publication->cptComments = Comments::countComments($publication->publication_id);
        $publication->post_at = self::timeAgo($publication->post_at);
        $publicationTable[] = $publication;
        // comment
        $comments = Comments::getComments($publication->publication_id);
        foreach ($comments as $comment) {
            $comment->answerList = self::getAnswer($comment->comment_id);
            $commentTable[] = $comment;
        }

        $this->render([
            "vue" => "comments",
            "title" => "COMMENTS",
            "user" => Users::getUser($user_id),
            "cptMessages" => Messages::countUnreadMessages($user_id),
            "userInfos" => UserInformation::getUserMoreInformation($user_id),
            "publicationList" => $publicationTable,
            "commentsList" => $commentTable
        ]);
    }

    public static function getAnswer($comment_id)
    {
        $a = [];
        $temp = [];
        $a = Answers::getAnswer($comment_id);
        foreach ($a as $b) {
            $b->to_user = Users::getUser($b->to_user_id);
            $temp[] = $b;
        }

        return $temp;
    }
}
