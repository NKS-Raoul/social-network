<?php

namespace App\Controller;

use App\App;
use App\Auth\DBAuth;
use App\Models\Comments;
use App\Models\Follow;
use App\Models\Likes;
use App\Models\Messages;
use App\Models\Publications;
use App\Models\UserInformation;
use App\Models\Users;

class ProfileController extends Controller
{
    public function index()
    {
        // $userInfos = 

        $user_id = (new DBAuth(App::getDb()))->getUserId();
        $user = Users::getUser($user_id);
        $userProfile[] = $user;
        $userProfile[] = UserInformation::getUserMoreInformation($user_id);
        // $this->updateCoverImage($user->user_nickname);
        // $this->updateProfileImage($user->user_nickname);
        $this->render([
            "vue" => "profile",
            "title" => "@" . $user->user_nickname,
            "user" => $user,
            "userProfile" => $userProfile,
            "userInfos" => UserInformation::getUserMoreInformation($user_id),
            "cptMessages" => Messages::countUnreadMessages($user_id),
            "publicationList" => self::getOtherPublicationsInfos($user_id),
            "supplementInfos" => self::manageSupplementInfos1($user_id)
        ]);
    }

    /**
     * the render view ! if it is not the current user's profile 
     */
    public function indexWithNickname($user_nickname)
    {
        // $userInfos = 
        $theUser_id = Users::getUserIdWithNickname($user_nickname);
        $user = Users::getUser((new DBAuth(App::getDb()))->getUserId());
        $userProfile[] = Users::getUser($theUser_id);
        $userProfile[] = UserInformation::getUserMoreInformation($theUser_id);
        // $this->updateCoverImage($user_nickname);
        // $this->updateProfileImage($user_nickname);
        $this->render([
            "vue" => "profile",
            "title" => "@" . $user->user_nickname,
            "user" => $user,
            "userProfile" => $userProfile,
            "userInfos" => UserInformation::getUserMoreInformation($user->user_id),
            "cptMessages" => Messages::countUnreadMessages($user->user_id),
            "publicationList" => self::getOtherPublicationsInfos($theUser_id),
            "supplementInfos" => self::manageSupplementInfos1($theUser_id)
        ]);
    }

    /**
     * method use to get more information on publications
     */
    public static function getOtherPublicationsInfos($user_id)
    {
        $publicationTable = Publications::getOnlyUserPublications($user_id);
        $renderTable = [];

        foreach ($publicationTable as $publication) {
            $publication->dislikes = Likes::getDislikes($publication->publication_id);
            $publication->likes = Likes::getLikes($publication->publication_id);
            $publication->my_like = Likes::getLikesOfUser($publication->publication_id, $user_id);
            $publication->comments = Comments::get2Comments($publication->publication_id);
            $publication->cptComments = Comments::countComments($publication->publication_id);
            $publication->post_at = self::timeAgo($publication->post_at);
            $renderTable[] = $publication;
        }

        return $renderTable;
    }

    public static function manageSupplementInfos1($user_id)
    {

        $user = UserInformation::getSupplementInfos($user_id);
        $user->cptFollower = count(Follow::selectFollowersId($user_id));
        $user->cptFollowing = count(Follow::selectFollowingsId($user_id));
        $user->verify = Follow::verify((new DBAuth(App::getDb()))->getUserId(), $user_id);

        return $user;
    }


    /**
     * method to change cover image
     */
    public function updateCoverImage($user_nickname)
    {
        if (isset($_POST['changeCover']) && !empty($_FILES)) {
            $typeTable = ["image/jpg", "image/jpeg", "image/png", "image/gif"];
            $file = $_FILES['change_cover_image'];


            if (in_array($file['type'], $typeTable)) {
                $temp = explode("/", $file['type']);
                $fileName = time() . "_" . intval($_SESSION['forum_user_id']) . "." . $temp[1];
                $destination = Controller::$filesRootPath . "users/covers/" . $fileName;
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    UserInformation::updateImage("cover_image", $fileName, intval($_SESSION['forum_user_id']));
                    // header("Location: ".$this->host."profile/".$user_nickname);
                    // header("location: " . $_SERVER['HTTP_REFERER']."/profile/".$user_nickname);
                    // exit();
                }
            }
        }

    }


    /**
     * method use to update profile image
     */
    public function updateProfileImage($user_nickname)
    {
        if (isset($_POST['changeProfile']) && !empty($_FILES)) {
            $typeTable = ["image/jpg", "image/jpeg", "image/png", "image/gif"];
            $file = $_FILES['change_profile_image'];


            if (in_array($file['type'], $typeTable)) {
                $temp = explode("/", $file['type']);
                $fileName = time() . "_" . intval($_SESSION['forum_user_id']) . "." . $temp[1];
                $destination = Controller::$filesRootPath . "users/profiles/" . $fileName;
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    UserInformation::updateImage("profile_image", $fileName, intval($_SESSION['forum_user_id']));
                    // header("Location: ".$this->host."profile/".$user_nickname);
                    // header("location: " . $_SERVER['HTTP_REFERER']."/profile/".$user_nickname);
                    // exit();
                }
            }
        }

        $this->indexWithNickname($user_nickname);
        // exit();
    }
}
