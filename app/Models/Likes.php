<?php

namespace App\Models;

use App\App;

class Likes extends Models
{
    /**
     * method to get likes
     */
    public static function getLikes($publication_id)
    {
        return self::query("SELECT COUNT(like_value) AS _like FROM likes
        WHERE (publication_id = ? AND like_value = ?)", [$publication_id, 1], true);
    }

    /**
     * method to get dislikes
     */
    public static function getDislikes($publication_id)
    {
        return self::query("SELECT COUNT(like_value) AS dislikes FROM likes
        WHERE (publication_id = ? AND like_value = ?)", [$publication_id, 2], true);
    }

    /**
     * method to get dislikes
     */
    public static function getLikesOfUser($publication_id, $user_id)
    {
        return self::query("SELECT like_value FROM likes
        WHERE (publication_id = ? AND user_id = ?)", [$publication_id, $user_id], true);
    }

    /**
     * method used to add like
     */
    public static function add($publication_id, $user_id, $like_value)
    {
        $temp = self::getLikesOfUser($publication_id, $user_id);
        $like = [];
        if ($temp) {
            if ($temp->like_value == $like_value) {
                $q = App::getDb()->getPDO()->prepare("DELETE FROM likes
                WHERE (publication_id = ? AND user_id = ?)");
                $q->execute([$publication_id, $user_id]);
            } else {
                $q = App::getDb()->getPDO()->prepare("UPDATE likes SET like_value = ? 
                WHERE (publication_id = ? AND user_id = ?)");
                $q->execute([$like_value, $publication_id, $user_id]);
            }
        } else {
            $q = App::getDb()->getPDO()->prepare("INSERT INTO likes(like_value, publication_id, user_id) 
            VALUES(?, ?, ?)");
            $q->execute([$like_value, $publication_id, $user_id]);
        }

        $like['likes'] = self::getLikes($publication_id);
        $like['dislikes'] = self::getDislikes($publication_id);
        $like['my_like'] = self::getLikesOfUser($publication_id, $user_id);
        return $like;
    }
}
