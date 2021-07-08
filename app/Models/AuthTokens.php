<?php

namespace App\Models;

use App\App;

class AuthTokens extends Models {

    // find the token of a user by the user's id
    public static function findByUserID($userID){
        return App::getDb()->prepare("SELECT * FROM authtokens WHERE user_id = ?", [$userID], null, true);
    }
}