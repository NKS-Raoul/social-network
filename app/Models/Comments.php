<?php

namespace App\Models;

use App\App;

class Comments extends Models
{
    public static function get2Comments($publication_id)
    {
        return self::query("SELECT comments.comment_id, comments.comment_text, comments.user_id,
        users.user_nickname, userinformation.profile_image, users.user_id 
        FROM comments
        LEFT JOIN users ON users.user_id = comments.user_id
        LEFT JOIN userinformation ON userinformation.user_id = comments.user_id
        WHERE comments.publication_id = ? ORDER BY post_at DESC LIMIT 2", [$publication_id], false);
    }

    public static function countComments($publications_id)
    {
        $temp = self::query(
            "SELECT COUNT(*) AS cptComments FROM comments WHERE publication_id = ?",
            [$publications_id],
            true
        );

        return $temp->cptComments;
    }

    /**
     * method using to add comment
     * 
     * @param string $comment_text the comments
     * @param int $publication_id the id of the publication
     * @param int $user_id the id of the user who add the comment
     * 
     * @return array
     */
    public static function add($comment_text, $publication_id, $user_id, $vue)
    {
        $q = App::getDb()->getPDO()->prepare("INSERT INTO comments(comment_text, publication_id, user_id)
        VALUES(?, ?, ?)");
        if ($q->execute([$comment_text, $publication_id, $user_id])) {
            if ($vue) {
                return self::getComments($publication_id);
            }
            return self::get2Comments($publication_id);
        } else {
            return [];
        }
    }

    public static function getComments($publication_id)
    {
        return self::query("SELECT comments.comment_id, comments.comment_text, comments.user_id,
        users.user_nickname, userinformation.profile_image, users.user_id 
        FROM comments
        LEFT JOIN users ON users.user_id = comments.user_id
        LEFT JOIN userinformation ON userinformation.user_id = comments.user_id
        WHERE comments.publication_id = ? ORDER BY post_at DESC", [$publication_id], false);
    }

    public static function delete($comment_id, $publication_id)
    {
        $q = App::getDb()->getPDO()->prepare("DELETE FROM comments WHERE comment_id = ?");
        if ($q->execute([$comment_id])) {
            return self::getComments($publication_id);
        } else {
            return [];
        }
        
    }
}
