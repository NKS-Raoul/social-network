<?php

namespace App\Models;

use App\App;

class Messages extends Models
{
    /**
     * method count unread message of one user
     */
    public static function countUnreadMessages($user_id)
    {
        return self::query("SELECT COUNT(*) AS unreadMessages 
        FROM messages WHERE receiver_id = ? AND seen = ?", [$user_id, 0], true);
    }

    /**
     * Static method which permit to add a message
     *
     * @param int $sender_id - Id of the sender
     * @param int $receiver_id - Id of the receiver
     * @param string $message - message contain
     * @return array
     */
    public static function add($sender_id, $receiver_id, $message)
    {
        $q = App::getDb()->getPDO()->prepare("INSERT INTO messages(sender_id, receiver_id, message, seen) 
        VALUES (?, ?, ?, ?)");

        // if the adding operation is well 
        if ($q->execute([$sender_id, $receiver_id, $message, 0])) {
            $nouveau_message = App::getDb()->prepare("SELECT * FROM messages WHERE id = ?", [App::getDb()->getPDO()->lastInsertId()], null, true);
            return array("success" => true, "message" => $nouveau_message);
        } else {
            return array("success" => false, "error" => "Error during the adding of the message");
        }
    }

    /**
     * Method which permit to get messages of a conversation
     *
     * @param int $id1 - Id of the current user
     * @param int $id2 - Id of the other user
     * @return array
     */
    public static function getMessages($id1, $id2)
    {
        /**
         * We get all messages which respect those conditions :
         *  - The current user is the sender and the second one is receiver
         *              Or
         *  - The current user is the receiver and the other one is sender
         * 
         *      We sort the list according to the date of post
         */
        $messages = self::query("SELECT messages.* FROM messages 
        WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) 
        ORDER BY send_time ASC", [$id1, $id2, $id2, $id1], false);

        /**
         * We change "seen" to "1", to mean that the current user has see the messages
         */
        $q = App::getDb()->getPDO()->prepare("UPDATE messages SET seen='1' 
        WHERE (sender_id = ? AND receiver_id = ?)");
        $q->execute([$id2, $id1]);

        // management of messages format
        if ($messages) {
            $return = [];

            // We create a field to mean if message is for the current or other user    
            foreach ($messages as $message) {
                $temp = $message;
                if ($message->sender_id == $id1) {
                    $temp->type = "send";
                } else {
                    $temp->type = "replies";
                }

                /**
                 * We get all the attachments (images or videos) and we add them to the attachments field
                 */
                $temp->attachments = self::query("SELECT * FROM messagesAttachment 
                WHERE message_id = ?", [$temp->id], false);

                $return[] = $temp;
            }

            return $return;
        } else {
            return [];
        }
    }

    /**
     * method to get last message
     */
    public static function getLastMessage($otherUser_id)
    {
        return self::query("SELECT * FROM messages 
        WHERE (sender_id=? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) 
        ORDER BY send_time DESC LIMIT 1", [self::$authObj->getUserId(), $otherUser_id, $otherUser_id, self::$authObj->getUserId()], true);
    }

    /**
     * method to get unread messages
     */
    public static function getUnreadMessages($otherUser_id, $user_id)
    {
        return self::query("SELECT id FROM messages 
            WHERE ((sender_id=? AND receiver_id = ?) AND seen='0')", [$otherUser_id, $user_id], false);
    }

    /**
     * method to change receiver state
     */
    public static function changeReceiverState($message_id)
    {
        $q = App::getDb()->getPDO()->prepare("UPDATE messages SET already_receive = '1' 
        WHERE id = ?");
        return $q->execute([$message_id]) ? true : false;
    }
}
