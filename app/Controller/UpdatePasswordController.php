<?php

namespace App\Controller;

use App\App;
use App\Auth\DBAuth;
use App\Models\Follow;
use App\Models\Messages;
use App\Models\UserInformation;
use App\Models\Users;

class UpdatePasswordController extends Controller
{
    public function index()
    {
        $user_id = (new DBAuth(App::getDb()))->getUserId();
        $user = Users::getUser($user_id);
        $userProfile[] = $user;
        $userProfile[] = UserInformation::getUserMoreInformation($user_id);
        self::updatePassword($user_id);
        $this->render([
            "vue" => "update_password",
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
     * method to change password
     */
    public static function updatePassword($user_id)
    {
        if (isset($_POST['savePassword']) && !empty($_POST['old_password'])) {
            $error = self::test_password($_POST['password'], $_POST['conf_password']);
            if (empty($error) && strcmp(Users::verifyOldPassword($user_id)->user_password, $_POST['old_password']) == 0) {
                Users::updatePassword($user_id, $_POST['password']);
                header("location: " . self::$host . "profile");
            } else {
                echo "<script>alert('" . $error . " Or the old password is empty')</script>";
            }
        }
    }

    /**
     * method using to test the password
     */
    public static function test_password($password = "", $confirm_password = "")
    {
        $error = "";
        if (strlen($password) < 8) {
            $error = "Your password must contain 8 characters";
        } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/", $password)) {
            $error = "Your password must contain capital letter, lowercase letter, number and special character";
        } elseif (strcmp($password, $confirm_password) != 0) {
            $error = "The two passwords must be the same";
        }
        return $error;
    }
}
