<?php

namespace App\AJAX;

use App\App;
use App\Auth\DBAuth;
use App\Controller\Controller;
use App\Controller\HomeController;
use App\Controller\MessengerController;
use App\Models\Answers;
use App\Models\AuthTokens;
use App\Models\Comments;
use App\Models\Follow;
use App\Models\Likes;
use App\Models\Messages;
use App\Models\MessagesAttachment;
use App\Models\Publications;
use App\Models\PublicationsAttachment;
use App\Models\UserInformation;
use App\Models\Users;

define('USER_ID', "user_id");


class AJAX
{
    /**
     * Data in request parameter
     */
    private $data;
    /**
     * string for user_id
     */
    private $string_user_id = 'user_id';
    /**
     * error of Forbidden
     */
    private $forbidden = "Forbidden action !";
    /**
     * error of Unknown user
     */
    private $unknown_user = "Forbidden action !";

    public static $root; // The root path of the project
    public static $host; // The domain name of the project
    public static $filesPath; // The files folder path 
    public static $assetsPath; // The assets folder path

    /**
     * The principal constructor that we will use to make asynchronous request
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        self::$root = $_SERVER['DOCUMENT_ROOT'] . "/forum/";
        self::$host = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/forum/";
        self::$filesPath = self::$root . "public/files/";
        self::$assetsPath = self::$host . "public/assets/";
        $this->data = $data;
    }

    /**
     * method using to add new publication
     */
    public function newPublication()
    {
        extract($this->data); // remove information in the POST
        $publicationId = 0;
        $result = [];
        if (!empty($text) || !empty($_FILES['publicationFile'])) {

            // token is use to know if the it is the current user who is adding publication
            $tokens = AuthTokens::all($this->string_user_id . " = " . $user_id);

            // if the user has a token
            if ($tokens) {
                $token = $tokens[0]->user_tokens;

                // if the token is for the current user
                if ($token == (new DBAuth(App::getDb()))->getUserToken()) {
                    // if the text field is not empty
                    if (!empty($text)) {
                        $result = Publications::addPublication($user_id, $text);
                        $result['publication']->post_at = Controller::timeAgo($result['publication']->post_at);
                        // using to add file
                        $publicationId = $result['publication']->publication_id;
                    } else {
                        $result = Publications::addPublication($user_id, "");
                        // using to add file
                        $publicationId = $result['publication']->publication_id;
                    }

                    // file management
                    if (!empty($_FILES['publicationFile'])) {
                        $result = self::testFile($_FILES, $publicationId, $result['publication']);
                    }
                } else {
                    $result = ["success" => false, "error" => $this->forbidden];
                }
            } else {
                $result = ["success" => false, "error" => $this->unknown_user];
            }
        } else {
            $result = ["success" => false, "error" => "Can't add publication all empty field"];
        }
        $this->render($result);
    }

    /**
     * method to add joined file on publication
     */
    public static function testFile($fileToTest, $publicationId, $publication)
    {
        // file management

        $file = $fileToTest['publicationFile'];
        $typeTable = ["image/jpg", "image/jpeg", "image/png", "image/gif", "video/mp4", "video/mkv", "video/avi"];
        if (in_array($file['type'], $typeTable)) {
            $temp = explode("/", $file['type']);
            // the new file name
            $fileName = time() . "_" . $publicationId . "." . $temp[1];
            // The new path of the file
            $destination = self::$filesPath . "publications/" . $temp[0] . "s/" . $fileName;
            // If the uploading is good
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                PublicationsAttachment::add($publicationId, $fileName, $temp[0]);
                return ["success" => true, "publication" => $publication];
            } else {
                return ["success" => false, "error" => "Error during uploading of file !"];
            }
        } else {
            return ["success" => false, "error" => "Unknown file type !"];
        }
    }


    /**
     * method to get publication
     */
    public function getPublications()
    {
        $bdAuth = new DBAuth(App::getDb());
        // token is use to know if the it is the current user who is adding publication
        $tokens = AuthTokens::all($this->string_user_id . " = " . $bdAuth->getUserId());

        // if the user has a token
        if ($tokens) {
            $token = $tokens[0]->user_tokens;

            // if the token is for the current user
            if ($token == $bdAuth->getUserToken()) {
                $result = ["success" => true, "publications" => HomeController::getOtherPublicationsInfos($bdAuth->getUserId())];
            } else {
                $result = ["success" => false, "error" => $this->forbidden];
            }
        } else {
            $result = ["success" => false, "error" => $this->unknown_user];
        }

        $this->render($result);
    }

    /**
     * add a comment
     */
    public function addComment()
    {
        extract($this->data);
        if (!empty($comment)) {
            $bdAuth = new DBAuth(App::getDb());
            // token is use to know if the it is the current user who is adding publication
            $tokens = AuthTokens::all($this->string_user_id . " = " . $bdAuth->getUserId());

            // if the user has a token
            if ($tokens) {
                $token = $tokens[0]->user_tokens;

                // if the token is for the current user
                if ($token == $bdAuth->getUserToken()) {
                    if (isset($vue) && $vue == "comments") {
                        $result = [
                            "success" => true,
                            "comments" => Comments::add($comment, $publication_id, $user_id, $vue),
                            "cptComments" => Comments::countComments($publication_id)
                        ];
                    } else {
                        $result = [
                            "success" => true,
                            "comments" => Comments::add($comment, $publication_id, $user_id, null),
                            "cptComments" => Comments::countComments($publication_id)
                        ];
                    }
                } else {
                    $result = ["success" => false, "error" => $this->forbidden];
                }
            } else {
                $result = ["success" => false, "error" => $this->unknown_user];
            }
        } else {
            $result = ["success" => false, "error" => "Can't post an empty comment 🤦‍♂️🤦‍♂️"];
        }

        $this->render($result);
    }


    /**
     * delete a comment
     */
    public function deleteComment()
    {
        extract($this->data);
        // token is use to know if the it is the current user who is adding publication
        $tokens = AuthTokens::all($this->string_user_id . " = " . $user_id);

        // if the user has a token
        if ($tokens) {
            $token = $tokens[0]->user_tokens;
            $bdAuth = new DBAuth(App::getDb());
            // if the token is for the current user
            if ($token == $bdAuth->getUserToken()) {
                $result = [
                    "success" => true,
                    "comments" => Comments::delete($comment_id, $publication_id),
                    "cptComments" => Comments::countComments($publication_id)
                ];
            } else {
                $result = ["success" => false, "error" => $this->forbidden];
            }
        } else {
            $result = ["success" => false, "error" => $this->unknown_user];
        }

        $this->render($result);
    }

    public function addAnswer()
    {
        extract($this->data);
        if (!empty($answer_text)) {
            $bdAuth = new DBAuth(App::getDb());
            // token is use to know if the it is the current user who is adding publication
            $tokens = AuthTokens::all($this->string_user_id . " = " . $user_id);

            // if the user has a token
            if ($tokens) {
                $token = $tokens[0]->user_tokens;

                // if the token is for the current user
                if ($token == $bdAuth->getUserToken()) {
                    $result = [
                        "success" => true,
                        "answers" => Answers::add($answer_text, $comment_id, $user_id, $to_user_id)
                    ];
                } else {
                    $result = ["success" => false, "error" => $this->forbidden];
                }
            } else {
                $result = ["success" => false, "error" => $this->unknown_user];
            }
        } else {
            $result = ["success" => false, "error" => "Can't post an empty comment 🤦‍♂️🤦‍♂️"];
        }

        $this->render($result);
    }

    /**
     * Method using to get online user
     */
    public function getOnlineUser()
    {
        $bdAuth = new DBAuth(App::getDb());
        // token is use to know if the it is the current user who is adding subject
        $tokens = AuthTokens::all($this->string_user_id . " = " . $bdAuth->getUserId());

        // if the user has a token
        if ($tokens) {
            $token = $tokens[0]->user_tokens;

            // if the token is for the current user
            if ($token == $bdAuth->getUserToken()) {
                $result = ["success" => true, "onlineUser" => Follow::selectInfosFollowings($bdAuth->getUserId())];
            } else {
                $result = ["success" => false, "error" => $this->forbidden];
            }
        } else {
            $result = ["success" => false, "error" => $this->unknown_user];
        }

        $this->render($result);
    }

    /**
     * method using for getting unread messages
     */
    public function getUnreadMessages()
    {
        $bdAuth = new DBAuth(App::getDb());
        // token is use to know if the it is the current user who is adding subject
        $tokens = AuthTokens::all($this->string_user_id . " = " . $bdAuth->getUserId());

        // if the user has a token
        if ($tokens) {
            $token = $tokens[0]->user_tokens;

            // if the token is for the current user
            if ($token == $bdAuth->getUserToken()) {
                $result = ["success" => true, "unreadMessage" => Messages::countUnreadMessages($bdAuth->getUserId())];
            } else {
                $result = ["success" => false, "error" => $this->forbidden];
            }
        } else {
            $result = ["success" => false, "error" => $this->unknown_user];
        }

        $this->render($result);
    }

    // Method to get the users who follower and suggest to follow back
    public function getFollow()
    {
        $bdAuth = new DBAuth(App::getDb());
        // token is use to know if the it is the current user who is adding subject
        $tokens = AuthTokens::all($this->string_user_id . " = " . $bdAuth->getUserId());

        // if the user has a token
        if ($tokens) {
            $token = $tokens[0]->user_tokens;

            // if the token is for the current user
            if ($token == $bdAuth->getUserToken()) {
                $result = ["success" => true, "follow" => Users::getSuggestionFollow($bdAuth->getUserId())];
            } else {
                $result = ["success" => false, "error" => $this->forbidden];
            }
        } else {
            $result = ["success" => false, "error" => $this->unknown_user];
        }

        $this->render($result);
    }

    public function getFollowBack()
    {
        $bdAuth = new DBAuth(App::getDb());
        // token is use to know if the it is the current user who is adding subject
        $tokens = AuthTokens::all($this->string_user_id . " = " . $bdAuth->getUserId());

        // if the user has a token
        if ($tokens) {
            $token = $tokens[0]->user_tokens;

            // if the token is for the current user
            if ($token == $bdAuth->getUserToken()) {
                $result = ["success" => true, "followBack" => Users::getSuggestionFollowBack($bdAuth->getUserId())];
            } else {
                $result = ["success" => false, "error" => $this->forbidden];
            }
        } else {
            $result = ["success" => false, "error" => $this->unknown_user];
        }

        $this->render($result);
    }

    /**
     * method used to add a followings
     */
    public function addFollowings()
    {
        extract($this->data);
        $bdAuth = new DBAuth(App::getDb());
        // token is use to know if the it is the current user who is adding subject
        $tokens = AuthTokens::all($this->string_user_id . " = " . $bdAuth->getUserId());

        // if the user has a token
        if ($tokens) {
            $token = $tokens[0]->user_tokens;

            // if the token is for the current user
            if ($token == $bdAuth->getUserToken()) {
                $result = ["success" => true, "message" => Follow::add($bdAuth->getUserId(), $following_id)];
            } else {
                $result = ["success" => false, "error" => $this->forbidden];
            }
        } else {
            $result = ["success" => false, "error" => $this->unknown_user];
        }

        $this->render($result);
    }



    /**
     * method used to add a like or a dislike
     */
    public function addLikes()
    {
        extract($this->data);
        $bdAuth = new DBAuth(App::getDb());
        // token is use to know if the it is the current user who is adding likes or dislikes
        $tokens = AuthTokens::all($this->string_user_id . " = " . $user_id);

        // if the user has a token
        if ($tokens) {
            $token = $tokens[0]->user_tokens;

            // if the token is for the current user
            if ($token == $bdAuth->getUserToken()) {
                $result = ["success" => true, "like" => Likes::add($publication_id, $user_id, $like_value)];
            } else {
                $result = ["success" => false, "error" => $this->forbidden];
            }
        } else {
            $result = ["success" => false, "error" => $this->unknown_user];
        }

        $this->render($result);
    }

    /**
     * delete a publication using the publication id
     */
    public function deletePublication()
    {
        extract($this->data);
        $bdAuth = new DBAuth(App::getDb());
        // token is use to know if the it is the current user who is adding subject
        $tokens = AuthTokens::all($this->string_user_id . " = " . $user_id);

        // $result = [];
        // if the user has a token
        if ($tokens) {
            $token = $tokens[0]->user_tokens;

            // if the token is for the current user
            if ($token == $bdAuth->getUserToken()) {
                $result = ["success" => true, "publications" => Publications::deletePublication($publication_id, $user_id)];
            } else {
                $result = ["success" => false, "error" => $this->forbidden];
            }
        } else {
            $result = ["success" => false, "error" => $this->unknown_user];
        }

        $this->render($result);
    }

    /**
     * This method is use to get conversation between two user 
     */
    public function getMessages()
    {
        $bdAuth = new DBAuth(App::getDb());

        // token is use to know if the it is the current user who is getting messages
        $tokens = AuthTokens::all($this->string_user_id . " = " . $bdAuth->getUserId());

        // if the user who is getting messages has a token 
        if ($tokens) {
            $token = $tokens[0]->user_tokens;

            // if the token that we get is the same with the token of current user
            if ($token == $bdAuth->getUserToken()) {
                $result = array("success" => true, "messages" => Messages::getMessages($bdAuth->getUserId(), $this->data['id']));
            } else {
                $result = array("success" => false, "error" => $this->forbidden);
            }
        } else {
            $result = array("success" => false, "error" => $this->unknown_user);
        }

        $this->render($result);
    }

    /**
     * this method is use to get all contacts
     */
    public function getContacts()
    {

        $bdAuth = new DBAuth(App::getDb());

        // token is use to know if the it is the current user who is getting contacts
        $tokens = AuthTokens::all($this->string_user_id . " = " . $bdAuth->getUserId());

        // if the user who is getting the contacts has a token 
        if ($tokens) {
            $token = $tokens[0]->user_tokens;

            // if the token that we get is the same with the token of current user
            if ($token == (new DBAuth(App::getDb()))->getUserToken()) {
                $result = array(
                    "success" => true,
                    "contacts" => MessengerController::contactsManagement($bdAuth->getUserId(), Users::getContacts($bdAuth->getUserId()))
                );
            } else {
                $result = array("success" => false, "error" => $this->forbidden);
            }
        } else {
            $result = array("success" => false, "error" => $this->unknown_user);
        }

        $this->render($result);
    }

    public function getContactsWithOther()
    {
        extract($this->data);
        $bdAuth = new DBAuth(App::getDb());

        // token is use to know if the it is the current user who is getting contacts
        $tokens = AuthTokens::all($this->string_user_id . " = " . $bdAuth->getUserId());

        // if the user who is getting the contacts has a token 
        if ($tokens) {
            $token = $tokens[0]->user_tokens;

            // if the token that we get is the same with the token of current user
            if ($token == (new DBAuth(App::getDb()))->getUserToken()) {
                $result = array(
                    "success" => true,
                    "contacts" => MessengerController::contactsManagement($bdAuth->getUserId(), Users::getContactsWithOther($bdAuth->getUserId(), $id))
                );
            } else {
                $result = array("success" => false, "error" => $this->forbidden);
            }
        } else {
            $result = array("success" => false, "error" => $this->unknown_user);
        }

        $this->render($result);
    }

    public function changeReceiverState()
    {
        extract($this->data);

        // token is use to know if the it is the current user who is getting contacts
        $tokens = AuthTokens::all($this->string_user_id . " = " . $user_id);

        // if the user who is getting the contacts has a token 
        if ($tokens) {
            $token = $tokens[0]->user_tokens;

            // if the token that we get is the same with the token of current user
            if ($token == (new DBAuth(App::getDb()))->getUserToken()) {
                $result = array(
                    "success" => Messages::changeReceiverState($message_id)
                );
            } else {
                $result = array("success" => false, "error" => $this->forbidden);
            }
        } else {
            $result = array("success" => false, "error" => $this->unknown_user);
        }

        $this->render($result);
    }


    /**
     * Method use to add a new message
     */
    public function newMessage()
    {

        /**
         * Transform elements into variables
         */
        extract($this->data);

        $messageID = 0;

        // if there is no empty fields
        if (!empty($user_id) && !empty($otherUser_id) && isset($message)) {

            // token is use to know if the it is the current user who is getting contacts
            $tokens = AuthTokens::all($this->string_user_id . " = " . $user_id);

            // if the user who is getting the contacts has a token
            if ($tokens) {
                $token = $tokens[0]->user_tokens;

                // if the token that we get is the same with the token of current user
                if ($token == (new DBAuth(App::getDb()))->getUserToken()) {
                    // $messageA = str_replace($this->assetsPath, "", $message);
                    $result = Messages::add($user_id, $otherUser_id, $message);
                    $messageID = $result['message']->id;
                } else {
                    $result = array("success" => false, "error" => $this->forbidden);
                }
            } else {
                $result = array("success" => false, "error" => $this->unknown_user);
            }
        } else {
            $result = array("success" => false, "error" => "There is an empty field !");
        }

        // if there are files in messages
        if (!empty($_FILES)) {
            $i = 0;
            // a loop for all the files 
            foreach ($_FILES as $file) {
                $temp = explode("/", $file['type']);
                $fileName = (time() + ($i++)) . "_" . $user_id . "." . $temp[1];
                $destination = self::$filesPath . "chats/" . $temp[0] . "s/" . $fileName;

                // file uploading
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    // if the file had been upload well
                    // $size = $file['size']; // the size of the file

                    // adding in the table
                    MessagesAttachment::add($fileName, $messageID, $temp[0]);
                }
            }
        }

        $this->render($result);
    }

    /**
     * method to change cover_image
     */
    public function changeCoverOrProfile($column)
    {
        extract($this->data);

        // token is use to know if the it is the current user who is getting contacts
        $tokens = AuthTokens::all($this->string_user_id . " = " . $user_id);

        // if the user who is getting the contacts has a token 
        if ($tokens) {
            $token = $tokens[0]->user_tokens;

            // if the token that we get is the same with the token of current user
            if ($token == (new DBAuth(App::getDb()))->getUserToken()) {
                if (!empty($_FILES['file'])) {
                    $file = $_FILES['file'];
                    $typeTable = ["image/jpg", "image/jpeg", "image/png", "image/gif"];
                    if (in_array($file['type'], $typeTable)) {
                        $temp = explode("/", $file['type']);
                        // the new file name
                        $fileName = time() . "_" . $user_id . "." . $temp[1];
                        $temp = $column == "cover_image" ? "covers/" : "profiles/";
                        // The new path of the file
                        $destination = self::$filesPath . "users/".$temp . $fileName;
                        // If the uploading is good
                        if (move_uploaded_file($file['tmp_name'], $destination)) {
                            UserInformation::updateImage($column, $fileName, $user_id);
                            $result = ["success" => true, "".$column => UserInformation::getUserMoreInformation($user_id)];
                        } else {
                            $result = ["success" => false, "error" => "Error during uploading of file !"];
                        }
                    } else {
                        $result = ["success" => false, "error" => "Unknown file type !"];
                    }
                }
            } else {
                $result = array("success" => false, "error" => $this->forbidden);
            }
        } else {
            $result = array("success" => false, "error" => $this->unknown_user);
        }

        $this->render($result);
    }

    public function search()
    {
        extract($this->data);

        // token is use to know if the it is the current user who is getting contacts
        $tokens = AuthTokens::all($this->string_user_id . " = " . $user_id);

        // if the user who is getting the contacts has a token 
        if ($tokens) {
            $token = $tokens[0]->user_tokens;

            // if the token that we get is the same with the token of current user
            if ($token == (new DBAuth(App::getDb()))->getUserToken()) {
                $finalList = [];
                $userList = Users::getUserWithWord($text);
                $publicationList = Publications::getPublicationWithWord($text);
                foreach ($userList as $a ) {
                    $a->type = "user";
                    $a->tempId = rand(1, 15);
                    $finalList[] = $a;
                }
                foreach ($publicationList as $b ) {
                    $b->type = "publication";
                    $b->comment_text = substr($b->comment_text, 0, 10);
                    $b->tempId = rand(1, 15);
                    $finalList[] = $b;
                }
                for ($i = 0; $i < count($finalList); $i++) {
                    for ($j = 0; $j < count($finalList) - ($i + 1); $j++) {
                        if ($finalList[$j]->tempId < $finalList[$j + 1]->tempId) {
                            $temp = $finalList[$j + 1];
                            $finalList[$j + 1] = $finalList[$j];
                            $finalList[$j] = $temp;
                        }
                    }
                }
                $result = array(
                    "searchList" => $finalList
                );
            } else {
                $result = array("success" => false, "error" => $this->forbidden);
            }
        } else {
            $result = array("success" => false, "error" => $this->unknown_user);
        }

        $this->render($result);
    }

    /**
     * Method which permit to render the Ajax result request. in JSON
     * 
     * @param array $data
     */
    public function render($data)
    {
        header("Content-Type: application/json");
        echo json_encode($data);
    }
}
