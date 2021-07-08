<?php


namespace App;

use App\AJAX\AJAX;
use App\Auth\DBAuth;
use App\Controller\AuthController;
use App\Controller\CommentsController;
use App\Controller\Controller;
use App\Controller\FollowersController;
use App\Controller\HomeController;
use App\Controller\MessengerController;
use App\Controller\ProfileController;
use App\Controller\UpdateInfoController;
use App\Controller\UpdateInfosController;
use App\Controller\UpdatePasswordController;
use App\Models\Comments;
use App\Models\Publications;
use App\Models\Users;

/**
 * Class for url path management and views choosing
 * The configuration is make in file .htaccess for 
 * URL Rewriting
 */
class Routeur
{

    /**
     * Content the name of the current page (the value of $_GET['page'])
     *
     * @var string
     */
    private $page;

    /**
     * Table which content the different possibles road that the user can access
     *
     * @var array
     */
    private $road = [
        "home" => "home",
        "login" => "login",
        "register" => "register",
        "addCourse" => "addCourse",
        "ajax" => [
            "addPublication", "getPublications", "getOnlineUser",
            "getUnreadMessages", "getFollow",
            "addFollowings", "addLikes", "deletePublication",
            "getMessages", "getContacts", "newMessage", "addComment",
            "getFollowBack", "getContactsWithOther", "deleteComment", "addAnswer",
            "changeReceiverState", "changeCover", "changeProfile", "search"
        ],
        "messenger" => "messenger",
        "profile" => "profile",
        "comments" => "comments",
        "update_infos" => "update_infos",
        "updatePassword" => "updatePassword",
        "followers" => "followers",
        "logout" => "logout"
    ];

    public function __construct($page)
    {
        $this->page = $page;
    }

    public function renderController()
    {
        $pages = explode('/', $this->page);
        $app = new App();
        $authObj = new DBAuth(App::getDb());

        // If the road exist in our road table
        if (key_exists($pages[0], $this->road)) {

            if (!$authObj->logged()) { // want to go home
                if ($pages[0] == "register") {
                    $controller = new AuthController();
                    $controller->register();
                } else {
                    $controller = new AuthController();
                    $controller->login();
                }
            } elseif ($authObj->logged()) { // there is a user online
                if ($pages[0] == "addCourse") {
                    $controller = new AuthController();
                    $controller->add_moreInfo();
                } elseif ($pages[0] == "ajax") {
                    if (!empty($pages[1]) && in_array($pages[1], $this->road['ajax'])) {
                        $ajax = new AJAX($_POST);
                        if ($pages[1] == "addPublication") {
                            $ajax->newPublication();
                        } elseif ($pages[1] == "getPublications") {
                            $ajax->getPublications();
                        } elseif ($pages[1] == "getOnlineUser") {
                            $ajax->getOnlineUser();
                        } elseif ($pages[1] == "getUnreadMessages") {
                            $ajax->getUnreadMessages();
                        } elseif ($pages[1] == "getFollow") {
                            $ajax->getFollow();
                        } elseif ($pages[1] == "getFollowBack") {
                            $ajax->getFollowBack();
                        } elseif ($pages[1] == "addFollowings") {
                            $ajax->addFollowings();
                        } elseif ($pages[1] == "addLikes") {
                            $ajax->addLikes();
                        } elseif ($pages[1] == "deletePublication") {
                            $ajax->deletePublication();
                        } elseif ($pages[1] == "getMessages") {
                            $ajax->getMessages();
                        } elseif ($pages[1] == "getContacts") {
                            $ajax->getContacts();
                        } elseif ($pages[1] == "getContactsWithOther") {
                            $ajax->getContactsWithOther();
                        } elseif ($pages[1] == "newMessage") {
                            $ajax->newMessage();
                        } elseif ($pages[1] == "addComment") {
                            $ajax->addComment();
                        } elseif ($pages[1] == "deleteComment") {
                            $ajax->deleteComment();
                        } elseif ($pages[1] == "addAnswer") {
                            $ajax->addAnswer();
                        } elseif ($pages[1] == "changeReceiverState") {
                            $ajax->changeReceiverState();
                        } elseif ($pages[1] == "changeCover") {
                            $ajax->changeCoverOrProfile("cover_image");
                        } elseif ($pages[1] == "changeProfile") {
                            $ajax->changeCoverOrProfile("profile_image");
                        }elseif ($pages[1] == "search") {
                            $ajax->search();
                        } else {
                            $app->notFound();
                        }
                    } else {
                        $app->notFound();
                    }
                } elseif ($pages[0] == "messenger") {
                    if (!empty($pages[1]) && !empty(Users::find($pages[1], "user_id")) && $pages[1] != $authObj->getUserId()) {
                        $controller = new MessengerController();
                        $controller->conversation(intval($pages[1]));
                    } else {
                        $controller = new MessengerController();
                        $controller->index();
                    }
                } elseif ($pages[0] == "profile") {
                    if (!empty($pages[1]) && !empty(Users::find($pages[1], "user_nickname"))) {
                        $controller = new ProfileController();
                        $controller->indexWithNickname($pages[1]);
                    } else {
                        $controller = new ProfileController();
                        $controller->index();
                    }
                } elseif ($pages[0] == "update_infos") {
                    $controller = new UpdateInfosController();
                    $controller->index();
                } elseif ($pages[0] == "updatePassword") {
                    $controller = new UpdatePasswordController();
                    $controller->index();
                } elseif ($pages[0] == "comments") {
                    $controller = new CommentsController();
                    $controller->getCommentsAndAnswers($pages[1]);
                } elseif ($pages[0] == "followers") {
                    if (!empty($pages[1]) && !empty(Users::find($pages[1], "user_nickname"))) {
                        $controller = new FollowersController();
                        $controller->indexWithNickname($pages[1]);
                    } else {
                        $controller = new FollowersController();
                        $controller->index();
                    }
                } elseif ($pages[0] == "logout") {
                    $authObj->logout();
                    header("location: " . $_SERVER['HTTP_REFERER']);
                    exit();
                } elseif ($pages[0] == "home") {
                    $controller = new HomeController();
                    $controller->index();
                } else {
                    $app->notFound();
                }
            }
        } else {
            $app->notFound();
        }
    }
}
