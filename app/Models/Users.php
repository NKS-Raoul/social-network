<?php

namespace App\Models;

use App\App;

/**
 * Class using to manage the users
 */
class Users extends Models
{

    /**
     * Method used to get a user using his id
     *
     * @param int $id - id of user
     */
    public static function getUser($id)
    {
        return self::query("SELECT user_id, user_name, user_nickname 
        FROM users WHERE user_id=?", [$id], true);
    }

    /**
     * Static method used to add a new user in database
     *
     * @param string $username - user name
     * @param string $login - Login of user
     * @param string $password - password of user
     * @param string $email - password of user
     * @return 
     */
    public static function add($name, $nickname, $password, $email)
    {
        $q = App::getDb()->getPDO()->prepare("INSERT INTO users (user_nickname, user_password, user_name, user_email) VALUES (?, ?, ?, ?)");
        $q->execute([$nickname, $password, $name, $email]);

        return App::getDb()->getPDO()->lastInsertId();
    }

    /**
     * Method used for add the profile image of user
     *
     * @param string $image - name of image file
     * @param int $userID - id of user
     * @return boolean
     */
    public static function updateImage($image, $userID)
    {
        $q = App::getDb()->getPDO()->prepare("UPDATE users SET image = ? WHERE id = ?");
        return $q->execute([$image, $userID]);
    }

    /**
     * method use to get persons who don't follow me and i don't back
     */
    public static function getSuggestionFollow($user_id)
    {
        return self::query("SELECT users.user_nickname, userinformation.profile_image, users.user_id
        FROM users
        LEFT JOIN userinformation ON userinformation.user_id = users.user_id
        WHERE (users.user_id NOT IN (SELECT followings_id AS id FROM follow WHERE followers_id = ?)
        AND users.user_id NOT IN (SELECT followers_id AS id FROM follow WHERE followings_id = ?)
        AND users.user_id <> ?) 
        LIMIT 7", [$user_id, $user_id, $user_id], false);
    }

    /**
     * method use to get persons who follow me and i don't back
     */
    public static function getSuggestionFollowBack($user_id)
    {
        return self::query("SELECT users.user_nickname, userinformation.profile_image, users.user_id
        FROM users
        LEFT JOIN userinformation ON userinformation.user_id = users.user_id
        WHERE (users.user_id NOT IN (SELECT followings_id AS id FROM follow WHERE followers_id = ?)
        AND users.user_id IN (SELECT followers_id AS id FROM follow WHERE followings_id = ?)
        AND users.user_id <> ?) 
        LIMIT 7", [$user_id, $user_id, $user_id], false);
    }

    /**
     * get contacts with the contacts we want to converse with
     */
    public static function getContactsWithOther($user_id, $otherUser_id)
    {
        return self::query("SELECT users.user_nickname, users.user_id, userinformation.profile_image 
        FROM users
        LEFT JOIN userinformation ON userinformation.user_id = users.user_id
        WHERE (users.user_id <> ? AND (users.user_id IN 
        (SELECT followings_id AS id FROM follow WHERE followers_id = ?) OR users.user_id IN 
        (SELECT sender_id AS id FROM messages WHERE receiver_id = ?) OR users.user_id IN
        (SELECT receiver_id AS id FROM messages WHERE sender_id = ?) 
        OR users.user_id = ?)) 
        ORDER BY users.user_nickname ASC", [$user_id, $user_id, $user_id, $user_id, $otherUser_id], false);
    }

    /**
     * get contacts with the contacts we want to converse with
     */
    public static function getContacts($user_id)
    {
        return self::query("SELECT users.user_nickname, users.user_id, userinformation.profile_image 
        FROM users
        LEFT JOIN userinformation ON userinformation.user_id = users.user_id
        WHERE (users.user_id <> ? AND (users.user_id IN 
        (SELECT followings_id AS id FROM follow WHERE followers_id = ?) OR users.user_id IN 
        (SELECT sender_id AS id FROM messages WHERE receiver_id = ?) OR users.user_id IN
        (SELECT receiver_id AS id FROM messages WHERE sender_id = ?))) 
        ORDER BY users.user_nickname ASC", [$user_id, $user_id, $user_id, $user_id], false);
    }

    /**
     * method use to get user id using the nickname
     */
    public static function getUserIdWithNickname($user_nickname)
    {
        $a = self::query("SELECT user_id FROM users WHERE user_nickname = ?", [$user_nickname], true);
        if ($a) {
            return $a->user_id;
        }
        return 0;
    }


    /**
     * method to update name and nickname of a user 
     */
    public static function updateNameNickname($name = "", $nickname = "", $email = "", $user_id = 0)
    {
        $q = App::getDb()->getPDO()->prepare('UPDATE users SET user_name = ?, user_nickname = ?, 
        user_email = ? WHERE user_id = ?');
        $q->execute([$name, $nickname, $email, $user_id]);
    }

    /**
     * method to update password
     */
    public static function updatePassword($user_id, $password)
    {
        $q = App::getDb()->getPDO()->prepare('UPDATE users SET user_password = ? 
        WHERE user_id = ?');
        $q->execute([$password, $user_id]);
    }

    /**
     * method to verify the old password
     */
    public static function verifyOldPassword($user_id)
    {
        return self::query("SELECT user_password FROM users WHERE user_id = ?", [$user_id], true);
    }

    /**
     * method to get follower
     */
    public static function getFollowers($user_id)
    {
        return self::query("SELECT users.user_nickname,users.user_name, userinformation.profile_image,
        userinformation.cover_image,userinformation.school_at FROM users
        LEFT JOIN userinformation ON users.user_id = userinformation.user_id
        WHERE users.user_id IN 
        (SELECT followers_id AS id FROM follow WHERE followings_id = ?)", [$user_id], false);
    }

    /**
     * method to search a user
     */
    public static function getUserWithWord($word)
    {
        $temp = '%'.$word.'%';
        return self::query("SELECT users.user_nickname, userinformation.profile_image
        FROM users 
        LEFT JOIN userinformation ON users.user_id = userinformation.user_id
        WHERE (users.user_nickname LIKE ? OR users.user_name LIKE ?)",
        [$temp,$temp], false);
    }
}
