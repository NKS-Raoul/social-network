<?php

namespace App\Models;

use App\App;

class UserInformation extends Models
{
    /**
     * Method used to get a more information using the id of user
     *
     * @param int $id - id of user
     */
    public static function getUser($id)
    {
        return self::query("SELECT * FROM userinformation WHERE user_id=?", [$id], true);
    }

    /**
     * Method used to add cover image using the id of user
     */
    public static function addUserId($user_id = 0)
    {
        $q = App::getDb()->getPDO()->prepare("INSERT INTO userinformation (user_id, cover_image, profile_image) 
        VALUES (?, ?, ?)");
        $q->execute([$user_id, "default.png", "default.png"]);
    }

    /**
     * Method used for add the profile image or cover image of user
     *
     * @param string $columnFile - cover or profile
     * @param string $path - path of image
     * @param int $userID - id of user
     * @return boolean
     */
    public static function updateImage($columnFile = "", $path = '', $userID = 0)
    {
        $q = App::getDb()->getPDO()->prepare("UPDATE userinformation SET " . $columnFile . " = ? WHERE user_id = ?");
        return $q->execute([$path, $userID]);
    }

    /**
     * method to change the state of user ! connected or not
     */
    public static function changeStatus($state, $user_id)
    {
        $q = App::getDb()->getPDO()->prepare("UPDATE userinformation SET user_state = ? WHERE user_id = ?");
        return $q->execute([$state, $user_id]);
    }

    /**
     * method to get more information about connected user
     */
    public static function getUserMoreInformation($user_id)
    {
        return self::query("SELECT cover_image, profile_image, cover_image, user_state, user_id
        FROM userinformation WHERE user_id = ?", [$user_id], true);
    }

    /**
     * get supplement information about a user
     */
    public static function getSupplementInfos($user_id)
    {
        return self::query("SELECT userinformation.school_at,
        userinformation.sex, userinformation.phoneNumber, userinformation.date_birth,
        userinformation.country, userinformation.descriptionI, users.user_email
        FROM userinformation
        LEFT JOIN users ON users.user_id = userinformation.user_id
        WHERE userinformation.user_id = ?", [$user_id], true);
    }

    /**
     * method to update information
     */
    public static function updateInformation($user_id, $school_at, $sex, $phoneNumber, $date_birth, $country, $description)
    {
        $q = App::getDb()->getPDO()->prepare("UPDATE userinformation SET 
        school_at = ?, sex = ?, phoneNumber = ?, date_birth = ?, country = ?, descriptionI = ? 
        WHERE user_id = ?");
        $q->execute([$school_at, $sex, $phoneNumber, $date_birth, $country, $description, $user_id]);
    }
}
