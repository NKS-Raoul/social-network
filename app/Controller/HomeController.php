<?php

namespace App\Controller;

use App\Models\Comments;
use App\Models\Follow;
use App\Models\Likes;
use App\Models\Messages;
use App\Models\Publications;
use App\Models\UserInformation;
use App\Models\Users;

class HomeController extends Controller
{
    public function index()
    {
        $user_id = self::$authObj->getUserId();
        $this->render([
            "title" => "FORUM",
            "vue" => "home",
            "user" => Users::getUser($user_id),
            "userInfos" => UserInformation::getUserMoreInformation($user_id),
            "cptMessages" => Messages::countUnreadMessages($user_id),
            "connectedUser" => Follow::selectInfosFollowings($user_id),
            "follow" => Users::getSuggestionFollow($user_id),
            "publicationList" => self::getOtherPublicationsInfos($user_id)
        ]);
    }

    public static function getOtherPublicationsInfos($user_id)
    {
        $publicationTable = Publications::getPublications($user_id);
        $renderTable = [];

        foreach ($publicationTable as $publication) {
            $publication->dislikes = Likes::getDislikes($publication->publication_id);
            $publication->likes = Likes::getLikes($publication->publication_id);
            $publication->my_like = Likes::getLikesOfUser($publication->publication_id, $user_id);
            if ($publication->my_like) {
                if (strcmp($publication->my_like->like_value, "1") == 0) {
                    $publication->strLike = "You and " . ($publication->likes->_like - 1) . " person(s) likes";
                } else {
                    $publication->strLike = $publication->likes->_like . " persons likes";
                }
            } else {
                $publication->strLike = $publication->likes->_like . " persons likes";
            }
            $publication->comments = Comments::get2Comments($publication->publication_id);
            $publication->cptComments = Comments::countComments($publication->publication_id);
            $publication->post_at = self::timeAgo($publication->post_at);
            $renderTable[] = $publication;
        }

        return $renderTable;
    }
}
