<?php

namespace App\Models;

use App\App;

class MessagesAttachment extends Models 
{
    /**
     * Adding of joined files
     *
     * @param string $fileName - files name
     * @param int $messageID - Id of the message
     * @param "image"|"video" $type - the type of the file
     * @return boolean
     */
    public static function add($fileName, $messageID, $type) {
        $q = App::getDb()->getPDO()->prepare("INSERT INTO messagesattachment(message_id, media, type) 
        VALUES (?, ?, ?)");
        $q->execute([$messageID, $fileName, $type]);
    }
}
