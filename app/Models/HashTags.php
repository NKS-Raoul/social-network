<?php
namespace App\Models;

use App\App;

class HashTags extends Models 
{
    public static function getTags($subject_id)
    {
       return self::query("SELECT hashTags FROM hashtags WHERE subject_id = ?", [$subject_id], false);
    }

    public static function add($subject_id = 0, $hashtags = [])
    {
        foreach ($hashtags as $hashtag) {
            $q = App::getDb()->getPDO()->prepare("INSERT INTO hashtags(hashTags, subject_id) VALUES (?, ?)");
            $q->execute([self::e($hashtag), $subject_id]);
        }
    }
}
