<?php

namespace App\Models;

use App\App;
use App\Controller\HomeController;

class Publications extends Models
{
    /**
     * method used to add publication
     * 
     * @param int $user_id the id of user
     * @param string $text the text of publication
     * 
     * @return array
     */
    public static function addPublication($user_id, $text)
    {   // we make the request
        $q = App::getDb()->getPDO()->prepare("INSERT INTO publications(user_id, publication_text)
        VALUES(?, ?)");
        // if request is good
        if ($q->execute([$user_id, nl2br(self::convertHashToColorText(self::e($text)))])) {
            // we get the new publication
            $new_publication = self::query("SELECT * FROM publications
            WHERE publication_id = ?", [App::getDb()->getPDO()->lastInsertId()], true);
            return ["success" => true, "publication" => $new_publication];
        } else {
            return ["success" => false, "error" => "Error during uploading"];
        }
    }


    /**
     * method using to get publication 
     */
    public static function getPublications($user_id)
    {
        return self::query("SELECT publications.*, users.user_nickname, userinformation.profile_image,
        publicationsattachment.media,publicationsattachment.type
        FROM publications
        LEFT JOIN users ON users.user_id = publications.user_id 
        LEFT JOIN userinformation ON userinformation.user_id = publications.user_id
        LEFT JOIN publicationsattachment ON publicationsattachment.publication_id = publications.publication_id
        WHERE (publications.user_id = ? OR publications.user_id IN
        (SELECT followings_id AS id FROM follow WHERE followers_id = ?))
        ORDER BY post_at DESC LIMIT 15", [$user_id, $user_id], false);
    }

    public static function getPublicationById($publication_id)
    {
        return self::query("SELECT publications.*, users.user_nickname, userinformation.profile_image,
        publicationsattachment.media,publicationsattachment.type
        FROM publications
        LEFT JOIN users ON users.user_id = publications.user_id 
        LEFT JOIN userinformation ON userinformation.user_id = publications.user_id
        LEFT JOIN publicationsattachment ON publicationsattachment.publication_id = publications.publication_id
        WHERE publications.publication_id = ?", [$publication_id], true);
    }

    /**
     * method to delete a publication
     */
    public static function deletePublication($publication_id, $user_id)
    {
        $q = App::getDb()->getPDO()->prepare("DELETE FROM publications WHERE publication_id = ?");

        if ($q->execute([$publication_id])) {
            return HomeController::getOtherPublicationsInfos($user_id);
        } else {
            return false;
        }
    }

    /**
     * method to get the publications of a person using his id
     */
    public static function getOnlyUserPublications($user_id)
    {
        return self::query("SELECT publications.*, users.user_nickname, userinformation.profile_image,
        publicationsattachment.media,publicationsattachment.type
        FROM publications
        LEFT JOIN users ON users.user_id = publications.user_id 
        LEFT JOIN userinformation ON userinformation.user_id = publications.user_id
        LEFT JOIN publicationsattachment ON publicationsattachment.publication_id = publications.publication_id
        WHERE publications.user_id = ? ORDER BY post_at DESC", [$user_id], false);
    }

    /**
     * method to get publications  
     */
    public static function getPublicationWithWord($text)
    {
        return self::query("SELECT publications.publication_id, publications.publication_text,
        comments.comment_text
        FROM publications
        LEFT JOIN userinformation ON publications.user_id = userinformation.user_id
        LEFT JOIN comments ON publications.publication_id = comments.publication_id
        WHERE (publications.publication_text LIKE ? OR comments.comment_text LIKE ?)",
        ["%".$text."%","%".$text."%"], false);
    }
}
