<?php

namespace App\Controller;

use App\Models\Messages;
use App\Models\UserInformation;
use App\Models\Users;

class MessengerController extends Controller
{
    public function index()
    {
        $user_id = self::$authObj->getUserId();
        $this->render([
            "title" => "MESSENGER",
            "vue" => "messenger",
            "user" => Users::getUser($user_id),
            "cptMessages" => Messages::countUnreadMessages($user_id),
            "userInfos" => UserInformation::getUserMoreInformation($user_id),
            "messages" => Messages::countUnreadMessages($user_id),
            "followBack" => Users::getSuggestionFollowBack($user_id),
            "contactsList" => json_encode(self::contactsManagement($user_id, Users::getContacts($user_id)))
        ]);
    }


    /**
     * Method using to display a specific conversation
     *
     * @param int $id - Id of the person we are conversing
     */
    public function conversation($id)
    {
        $user_id = self::$authObj->getUserId();
        // we get information about user we are conversing
        $user = Users::getUser($id);

        // we get the messages list between the both
        $messages = Messages::getMessages($user_id, $id);
        foreach ($messages as $message ) {
            $regex_img = "/&lt;img src=&quot;imgSmileys2021smileys\/(\S*).gif&quot;&gt;/";
            $tempo = preg_replace($regex_img, "<img src=\"".self::$assetsPath."smileys/$1.gif\">", $message->message);
            $message->message = preg_replace("/&lt;br&gt;/", "", $tempo);
        }
        /**
         * We render the view
         */
        $this->render([
            "vue" => "messenger",
            "title" => "@" . $user->user_nickname,
            "otherUser" => $user,
            "messages" => json_encode($messages),
            "user" => Users::getUser($user_id),
            "userInfos" => UserInformation::getUserMoreInformation($user_id),
            "cptMessages" => Messages::countUnreadMessages($user_id),
            "followBack" => Users::getSuggestionFollowBack($user_id),
            "contactsList" => json_encode(self::contactsManagement($user_id, Users::getContactsWithOther($user_id, $id))),
            "smileyTable" => self::getSmiley()
        ]);
    }


    public static function contactsManagement($user_id, $contacts = [])
    {
        $return = [];

        // we make a loop on the array to add some fields  
        foreach ($contacts as $contact) {

            $temp = $contact;
            $lastMessage = Messages::getLastMessage($temp->user_id);
            /**
             * getting of the unread messages
             */
            $unreadMessages = Messages::getUnreadMessages($contact->user_id, $user_id);

            /**
             * if we get something, we return the number of messages else 0
             */
            if ($unreadMessages) {
                $temp->unreadMessages = count($unreadMessages);
            } else {
                $temp->unreadMessages = 0;
            }

            /**
             * if there is a last massage, we format it in view for the current user
             *      - a preview : which is the text to display
             *      - the preview is writing according to if the actual user is the sender or not 
             *      - we get the date of the last message of conversation
             */
            if ($lastMessage) {
                if ($lastMessage->sender_id == $user_id) {
                    $temp->preview = '<strong class="siteColor">You:</strong> ' . $lastMessage->message;
                } else {
                    $temp->preview = $lastMessage->message;
                }
                $temp->lastMessageDate = self::timeAgo($lastMessage->send_time);
                $temp->lastMessageId = $lastMessage->id;
                $temp->lastMessageAlready_receive = $lastMessage->already_receive;
            } else {
                $temp->preview = "";
                $temp->lastMessageDate = 0;
                $temp->lastMessageId = 0;
            }


            $return[] = $temp;
        }

        return self::sort($return);
    }


    /**
     * method to sort an array
     */
    public static function sort($return)
    {
        for ($i = 0; $i < count($return); $i++) {
            for ($j = 0; $j < count($return) - ($i + 1); $j++) {
                if ($return[$j]->lastMessageId < $return[$j + 1]->lastMessageId) {
                    $temp = $return[$j + 1];
                    $return[$j + 1] = $return[$j];
                    $return[$j] = $temp;
                }
            }
        }

        return $return;
    }

    /**
     * a time ago for messages 
     */
    public static function timeAgo($date)
    {
        $timestamp = strtotime($date);

        $strTime = ["second", "minute", "hour", "day", "month", "year"];
        $length = ["60", "60", "24", "30", "12", "10"];

        $currentTime = time();
        if ($currentTime >= $timestamp) {
            $diff     = time() - $timestamp;
            for ($i = 0; $diff >= $length[$i] && $i < count($length) - 1; $i++) {
                $diff = $diff / $length[$i];
            }

            $diff = round($diff);
            if ($i == 3) {
                $format = ($diff == 1) ? "Yesterday" : $diff . " " . $strTime[$i] . "s";
            } elseif ($i == 0 && $diff < 60) {
                $format = "Now";
            } else {
                $format = $diff . " " . $strTime[$i] . ($diff == 1 ? "" : "s");
            }

            return $format;
        }
    }

    /**
     * function to get smiley
     */
    public static function getSmiley()
    {
        $rootDir = "./public/assets/";
        $currentDir = "smileys/";
        $smileyTable = [];

        if (!is_dir($rootDir)) {
            die("Unable to get access to rootDir, contact your web administrator.".$rootDir);
        }
        $directory = opendir($rootDir.$currentDir);
        $foundOne = false;
        $i = 0;
        while ($file = readdir($directory)) {
            if (is_file($rootDir . $currentDir . $file)) {
                $foundOne = true;
                $i++;
                $smileyTable[] = '<div class="md-sm-3 smiley ctrContent"><img src="' . self::$assetsPath . $currentDir . $file . '" /></div>';
            }
        }
        closedir($directory);
        if (!$foundOne) {
            echo "Unexpected file !";
        }

        return $smileyTable;
    }
}
