<?php

namespace App\Controller;

use App\App;
use App\Auth\DBAuth;
use App\Models\Models;
use App\Models\Users;
use App\Models\UserInformation;

/**
 * Principal class for register and login management  
 */
class AuthController extends Controller
{

    /**
     * principal function using to log in a user
     */
    public function login()
    {
        if (isset($_POST['connect'])) { // if the user submit the form
            // we extract information in $_POST and transform keys in variable
            extract($_POST);

            if (Models::not_empty(["username", "password"])) {
                if ((new DBAuth(App::getDb()))->login($username, $password)) {
                    header("location: " . Controller::$host);
                    exit();
                } else {
                    $this->render([
                        "vue" => "Login",
                        "title" => "login error",
                        "login_error" => "No user with those information"
                    ]);
                    exit();
                }
            } else { // if one of them if empty
                // if the password field is empty and not the username field
                if (!Models::not_empty(["password"]) && Models::not_empty(["username"])) {
                    $password_error = "You must enter the password here";

                    $this->render([
                        "vue" => "login",
                        "title" => "Log In",
                        "password_error" => $password_error,
                        "username" => $_POST['username']
                    ]);
                    exit();
                } else if (Models::not_empty(["password"]) && !Models::not_empty(["username"])) {
                    // if the username field is empty and not the password field

                    $name_error = "You must enter the nickname or email here";
                    $this->render([
                        "vue" => "login",
                        "title" => "Log In",
                        "name_error" => $name_error
                    ]);
                    exit();
                } else { // if the both are empty
                    $password_error = "You must enter the password here";
                    $name_error = "You must enter the nickname or email here";
                    $this->render([
                        "vue" => "login",
                        "title" => "log In",
                        "password_error" => $password_error,
                        "name_error" => $name_error,
                        "username" => $_POST['username']
                    ]);
                    exit();
                }
            }
        }

        $this->render([
            "vue" => "Login",
            "title" => "log In"
        ]);
    }

    /**
     * Principal function to register a user
     */
    public function register()
    {
        // variables whose will content errors 
        $name_error = "";
        $nickname_error = "";
        $email_error = "";
        $password_error = "";
        $confirm_password_error = "";

        // when the user submit the form
        if (isset($_POST['signUp'])) {
            // If important field is not empty
            if (Models::not_empty(["name", "nickname", "password", "confirm_password"])) {
                // we extract information in $_POST and transform keys in variable
                extract($_POST);

                // Name verification
                $name_error = self::test_name($name);

                // Nickname verification
                $nickname_error = self::test_nickname($nickname);

                // password verification
                $password_error = self::test_password($password, $confirm_password);

                if (empty($name_error) && empty($nickname_error) && empty($password_error)) {

                    $new_user = Users::add($name, $nickname, $password, $email);
                    UserInformation::addUserId($new_user);

                    // if user is well added
                    if ($new_user) {
                        $dd = (new DBAuth(App::getDb()));
                        $dd->login($nickname, $password);
                        header("Location: " . $this->$host . "addCourse");
                        exit();
                    } else {
                        $this->render([
                            "vue" => "register",
                            "title" => "register",
                            "register_error" => "The account was not created ! Please again"
                        ]);
                        exit();
                    }
                }
            } else {
                $name_error = "Name is required !";
                $nickname_error = "Nickname is required !";
                $password_error = "Password is required !";
                $confirm_password_error = "Confirm password is required !";
            }
        }




        $this->render([
            "vue" => "register",
            "title" => "register",
            "name_error" => $name_error,
            "nickname_error" => $nickname_error,
            "email_error" => $email_error,
            "password_error" => $password_error,
            "confirm_password_error" => $confirm_password_error,
            // value variables
            "nameValue" => isset($name) ? $name : "",
            "nicknameValue" => isset($nickname) ? $nickname : "",
            "emailValue" => isset($email) ? $email : "",
        ]);
    }

    /**
     * method using to test the password
     */
    static function test_password($password = "", $confirm_password = "")
    {
        $error = "";
        if (strlen($password) < 8) {
            $error = "Your password must contain 8 characters";
        } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/", $password)) {
            $error = "Your password must contain capital letter, lowercase letter, number and special character";
        } elseif (strcmp($password, $confirm_password) != 0) {
            $error = "The two passwords must be the same";
        }
        return $error;
    }

    /**
     * method using to test the nickname
     */
    static function test_nickname($nickname = "")
    {
        if (strlen($nickname) > 15) {
            return "The nickname must have maximum 15 characters";
        } elseif (Users::all("user_nickname = '" . $nickname . "'")) {
            return "A person already use that Nickname";
        } else {
            return "";
        }
    }

    /**
     * method using to test the name
     */
    static function test_name($name = "")
    {
        if (strlen($name) > 20) {
            return "The name must have maximum 20 characters";
        } elseif (!preg_match("/^[A-Z]/", $name)) {
            return "The name must start with capital letter";
        } else {
            return "";
        }
    }


    /**
     * function for adding cover image, profile image and course of discussion
     */
    public function add_moreInfo()
    {
        $choose_course_error = "";

        if (isset($_POST['addCourse'])) {

            self::profileManagement($_FILES['new_background'], "cover_image", "covers");
            self::profileManagement($_FILES['new_profile_img'], "profile_image", "profiles");

            header("Location: " . self::$host);
        }

        $this->render([
            "vue" => "newProfile",
            "title" => "More information",
            "choose_course_error" => $choose_course_error
        ]);
    }

    /**
     * method used to add cover and profile images
     * 
     * @param array $file array of file 
     * @param string $column which column to update in table  
     * @param string $place where to put the file
     */
    public static function profileManagement($file = [], $column = "", $place = "")
    {
        $typeTable = ["image/jpg", "image/jpeg", "image/png", "image/gif"];
        if (isset($file) && !empty($file) && !empty($file['type'])) {
            if (in_array($file['type'], $typeTable)) {
                $temp = explode("/", $file['type']);
                $fileName = time() . "_" . intval($_SESSION['forum_user_id']) . "." . $temp[1];
                $destination = Controller::$filesRootPath . "users/" . $place . "/" . $fileName;
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    UserInformation::updateImage($column, $fileName, intval($_SESSION['forum_user_id']));
                }
            } else {
                $con = new Controller();
                $con->render([
                    "vue" => "newProfile",
                    "title" => "More information",
                    "choose_course_error" => "Error on file choice! Accept jpeg, jpg, png or gif Not" . $file['type']
                ]);
                exit();
            }
        }
    }
}
