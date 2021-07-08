<?php

namespace App\Controller;

use App\App;
use App\Auth\DBAuth;
use App\Models\Follow;
use App\Models\Messages;
use App\Models\UserInformation;
use App\Models\Users;

class FollowersController extends Controller 
{
    public function index()
    {

        $user_id = (new DBAuth(App::getDb()))->getUserId();
        $user = Users::getUser($user_id);
        $userProfile[] = $user;
        $userProfile[] = UserInformation::getUserMoreInformation($user_id);
        $this->render([
            "vue" => "profileFollowers",
            "title" => "@" . $user->user_nickname,
            "user" => $user,
            // "userProfile" => $userProfile,
            "userInfos" => UserInformation::getUserMoreInformation($user_id),
            "cptMessages" => Messages::countUnreadMessages($user_id),
            "supplementInfos" => self::manageSupplementInfos1($user_id),
            "followers" => Users::getFollowers($user_id)
        ]);
    }

    public function indexWithNickname($user_nickname)
    {
        $CurrentUser_id = (new DBAuth(App::getDb()))->getUserId();
        $CurrentUser = Users::getUser($CurrentUser_id);
        $user_id = Users::getUserIdWithNickname($user_nickname);
        $user = Users::getUser($user_id);
        $userProfile[] = $user;
        $userProfile[] = UserInformation::getUserMoreInformation($user_id);
        $this->render([
            "vue" => "profileFollowers",
            "title" => "@" . $user->user_nickname,
            "user" => $CurrentUser,
            "userProfile" => $userProfile,
            "userInfos" => UserInformation::getUserMoreInformation($CurrentUser_id),
            "cptMessages" => Messages::countUnreadMessages($CurrentUser_id),
            "supplementInfos" => self::manageSupplementInfos1($user_id),
            "followers" => Users::getFollowers($user_id)
        ]);
    }

    /**
     * method to get more information
     */
    public static function manageSupplementInfos1($user_id)
    {

        $user = UserInformation::getSupplementInfos($user_id);
        $user->cptFollower = count(Follow::selectFollowersId($user_id));
        $user->cptFollowing = count(Follow::selectFollowingsId($user_id));
        $user->verify = Follow::verify((new DBAuth(App::getDb()))->getUserId(), $user_id);

        return $user;
    }
}
