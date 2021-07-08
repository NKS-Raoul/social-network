<?php

namespace App\Models;

use App\App;

/**
 * class for Follow table in database
 */
class Follow extends Models
{
    /**
     * method to select all followings id (persons the user follow)
     */
    public static function selectFollowingsId($user_id)
    {
        return self::query("SELECT followings_id FROM follow WHERE followers_id = ?", [$user_id], false);
    }

    /**
     * method to select all followings id (persons the user follow)
     */
    public static function selectFollowersId($user_id)
    {
        return self::query("SELECT followers_id FROM follow WHERE followings_id = ?", [$user_id], false);
    }

    /**
     * method to get more information about persons users follow 
     * and they are online
     * 
     * @return array
     */
    public static function selectInfosFollowings($user_id)
    {
        return self::query("SELECT users.user_nickname, userinformation.profile_image, follow.followings_id
        FROM follow 
        INNER JOIN userinformation ON userinformation.user_id = follow.followings_id
        INNER JOIN users ON users.user_id = follow.followings_id
        WHERE follow.followers_id = ? AND userinformation.user_state = '1' LIMIT 15", [$user_id], false);
    }

    /**
     * method to know if user following another user
     */
    public static function followers($user_id, $anotherUser_id)
    {
        $q = App::getDb()->getPDO()->prepare("SELECT * FROM follow WHERE 
        (followers_id = ? AND followings_id = ?)");
        $q->execute([$user_id, $anotherUser_id]);

        return $q->rowCount() ?  true : false;
    }

    /**
     * method to add following
     */
    public static function add($user_id, $anotherUser_id)
    {
        $q = App::getDb()->getPDO()->prepare("INSERT INTO follow(followers_id, followings_id) VALUES (?, ?)");
        $q->execute([$user_id, $anotherUser_id]);

        return $q? true : false; 
    }

    /**
     * verify me following a user(i'm the follower)
     */
    public static function verify($user_id, $anotherUser_id)
    {
        return self::query("SELECT * FROM follow WHERE followers_id = ? AND followings_id = ?",
        [$user_id, $anotherUser_id], true);
    }
}
