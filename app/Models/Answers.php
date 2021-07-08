<?php

namespace App\Models;

use App\App;
use App\Controller\CommentsController;

class Answers extends Models
{
    /**
     * method to count answers of a subject
     */
    public static function countAnswers($subject_id)
    {
        return self::query("SELECT COUNT(*) AS cptAnswers 
        FROM answers WHERE subject_id = ?", [$subject_id], true);
    }

    public static function getAnswer($comment_id)
    {
        return self::query("SELECT answers.*, users.user_nickname,users.user_nickname AS to_nickname , userinformation.profile_image
        FROM answers
        LEFT JOIN users ON users.user_id = answers.user_id
        LEFT JOIN userinformation ON userinformation.user_id = answers.user_id
        WHERE comment_id = ? ORDER BY post_at DESC", [$comment_id], false);
    }

    public static function add($answer_text, $comment_id, $user_id, $to_user_id)
    {
        $q = App::getDb()->getPDO()->prepare("INSERT INTO answers(user_id, to_user_id, comment_id, answer)
        VALUES(?, ?, ?, ?)");
        if ($q->execute([$user_id, $to_user_id, $comment_id, $answer_text])) {
            return CommentsController::getAnswer($comment_id);
        } else {
            return [];
        }
    }
}
