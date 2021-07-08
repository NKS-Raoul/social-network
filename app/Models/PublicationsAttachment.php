<?php

namespace App\Models;

use App\App;

class PublicationsAttachment extends Models 
{
    /**
     * method used to add publications file
     */
    public static function add($publication_id, $file_name, $file_type)
    {
        $q = App::getDb()->getPDO()->prepare("INSERT INTO 
        publicationsattachment(publication_id, media, type) VALUES (?, ?, ?)");
        $q->execute([$publication_id, $file_name, $file_type]);
    }
}
