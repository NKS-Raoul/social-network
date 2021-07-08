<?php

namespace App\Models;

use App\App;

class CourseList extends Models
{
    /**
     * Method used to get a course using the id of user
     *
     * @param int $id - id of user
     */
    public static function getCourse($id)
    {
        return self::query("SELECT course_name FROM courselist WHERE user_id=?", [$id], false);
    }

    /**
     * Method used to add courses using the id of user
     */
    public static function add($course = "", $user_id = 0)
    {
        $q = App::getDb()->getPDO()->prepare("INSERT INTO courselist (user_id, course_name) VALUES (?, ?)");
        $q->execute([$user_id, $course]);
    }
}
