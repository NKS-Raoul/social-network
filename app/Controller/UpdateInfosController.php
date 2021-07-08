<?php

namespace App\Controller;

use App\App;
use App\Auth\DBAuth;
use App\Models\Follow;
use App\Models\Messages;
use App\Models\UserInformation;
use App\Models\Users;

class UpdateInfosController extends Controller
{
    public function index()
    {

        $user_id = (new DBAuth(App::getDb()))->getUserId();
        $user = Users::getUser($user_id);
        $userProfile[] = $user;
        $userProfile[] = UserInformation::getUserMoreInformation($user_id);
        self::addMoreInformation($user_id);
        $this->render([
            "vue" => "update_infos",
            "title" => "@" . $user->user_nickname,
            "user" => $user,
            "userProfile" => $userProfile,
            "userInfos" => UserInformation::getUserMoreInformation($user_id),
            "cptMessages" => Messages::countUnreadMessages($user_id),
            "supplementInfos" => self::manageSupplementInfos1($user_id)
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

    /**
     * method to add more information
     */
    public static function addMoreInformation($user_id)
    {
        if (isset($_POST['saveChanges']) && !empty($_POST['name'] && !empty($_POST['nickname']))) {
            Users::updateNameNickname($_POST['name'], $_POST['nickname'], $_POST['email'], $user_id);
            UserInformation::updateInformation(
                $user_id,
                $_POST['school'],
                $_POST['sex'],
                $_POST['phoneNumber'],
                $_POST['birth_date'],
                $_POST['country'],
                $_POST['description']
            );
            header("location: ".self::$host."profile/");
        }
    }
}
