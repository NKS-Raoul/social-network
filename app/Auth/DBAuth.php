<?php

namespace App\Auth;

use App\Database\Database;
use App\Models\AuthTokens;
use App\Models\UserInformation;
use App\Models\Users;

/**
 * This class manage the authentication of different users
 */
class DBAuth
{

    /**
     * Instance of the database to use
     *
     * @var Database
     */
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Method which permit to connect a user to the database and return a boolean 
     *      if the connection was making or not
     * 
     * @param string $identifier
     * @param string $password
     * @return boolean
     */
    public function login($identifier, $password)
    {
        $user = $this->db->prepare(
            "SELECT user_id, user_password FROM users
            WHERE (user_nickname = ? AND user_password = ?) OR (user_email = ? AND user_password = ?)",
            [$identifier, $password, $identifier,$password],
            null,
            true
        );

        // If the user is found, we registry the "Id" in session
        if ($user) {

            // when we connect the user, we registry his token.
            // To assure the oneness of token, we take the timestamp of user connection to database,
            // we concat with the random number between 0 and 9 and we ache it (sha1);
            $token = sha1(time() . rand(0, 9));

            // If the user had been already connect, we update the token
            if (AuthTokens::findByUserID($user->user_id)) {
                $q = $this->db->getPDO()->prepare("UPDATE authTokens SET user_tokens = ? WHERE user_id = ?");
                $q->execute([$token, $user->user_id]);
            } else { // we insert the token in database
                $q = $this->db->getPDO()->prepare("INSERT INTO authTokens (user_id, user_tokens) VALUES (?, ?)");
                $q->execute([$user->user_id, $token]);
            }

            UserInformation::changeStatus(1, $user->user_id);
            $_SESSION['forum_auth_token'] = $token; // We add the token of connected user in session
            $_SESSION['forum_user_id'] = $user->user_id; // We add the id of connected user in session

            return true;
        }
        return false;
    }

    /**
     * Method which return the Id of connected user
     *
     * @return int (Id) if the user is connected
     * @return false otherwise
     */
    public static function getUserId()
    {
        if (isset($_SESSION['forum_user_id'])) {
            return intval($_SESSION['forum_user_id']);
        }

        return false;
    }

    /**
     * Method which return the token of connected user
     *
     * @return string (The token) if the user is connected
     * @return false otherwise
     */
    public static function getUserToken()
    {
        if (isset($_SESSION['forum_auth_token'])) {
            return $_SESSION['forum_auth_token'];
        }

        return false;
    }


    /**
     * Method which return all information about a user using his Id
     * 
     * @return Users
     */
    public static function getCurrentUser()
    {
        return Users::getUser(self::getUserId());
    }

    /**
     * Logout of an user! We destroy the current session
     */
    public function logout()
    {
        UserInformation::changeStatus(0, $_SESSION['forum_user_id']);
        session_destroy();
    }

    /**
     * Method to verify if a user is connect 😎
     *
     * @return boolean
     */
    public function logged()
    {
        return (isset($_SESSION['forum_user_id']));
    }
}
