<?php

namespace App;

use App\Database\Database;

/**
 * It is the principal class ! The class which define the application
 */
class App{

    const DB_NAME = "forum";
    const DB_USER = "root";
    const DB_PASS = "";
    const DB_HOST = "localhost";

    /**
     * Variable which content the instance of database
     *
     * @var Database
     */
    private static $database;

    /**
     * Method which permit to return the instance of the database which will be used in all the project
     *
     * @return Database
     */
    public static function getDb(){
        if(self::$database == null){
            self::$database = new Database(self::DB_NAME, self::DB_USER, self::DB_PASS, self::DB_HOST);
        }
        
        return self::$database;
    }

    /**
     * Function to activate when the user enter somewhere with forbidden access
     */
    public function forbidden(){
        header('HTTP/1.0 403 Forbidden');
        die("Forbidden Access !");
    }

    /**
     * Function to activate when the user try to go somewhere not existing
     */
    public function notFound(){
        header('HTTP/1.0 404 Not Found');
        die("Not Found Page !");
    }

}

