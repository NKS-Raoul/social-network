<?php

namespace App\Models;

use App\App;
use App\Auth\DBAuth;
use App\Database\Database;

/**
 * Principal class which will content the methods can be used by all the others Models
 */
class Models
{
    /**
     * @var string $table Selected table
     */
    protected static $table;

    /**
     * @var Database $database Instance of selected database
     */
    protected static $database;

    /**
     * @var DBAuth $authObj Instance of authentication
     */
    protected static $authObj;

    /**
     * The constructor
     */
    public function __construct()
    {
        self::$database = new Database();
        self::$authObj = (new DBAuth(App::getDb()));
    }

    /**
     * Function allow to get the table to use according to the class which is call
     *
     * @return string
     */
    private static function getTable()
    {
        if (self::$table == null) {
            $class_name = explode("\\", get_called_class());
            self::$table = strtolower(end($class_name));
        }

        return self::$table;
    }

    /**
     * Permit to verify if all POST values is not empty for the future using (also in protected)
     * 
     * @param array $champs The list of fields to control
     * @return boolean;
     */
    public static function not_empty($champs = [])
    {
        if (count($champs) != 0) {
            foreach ($champs as $champ) {
                if (empty($_POST[$champ]) || trim($_POST[$champ]) == "") {
                    return false;
                }
            }

            return true;
        }
        return false;
    }

    /**
     * Function permitted to convert special character to HTML entities
     *
     * @param string $string string to convert
     * @return string
     */
    protected static function e($string)
    {
        return htmlspecialchars($string);
    }

    /**
     * The query function permit to make request to the database
     *
     * @param string $statement The request
     * @param array $attributes The attributes (for prepare request)
     * @param boolean $one détermine if we want one result
     * 
     * @return mixed
     */
    public static function query($statement, $attributes = null, $one = false)
    {
        if ($attributes) {
            return App::getDb()->prepare($statement, $attributes, get_called_class(), $one);
        } else {
            return App::getDb()->query($statement, get_called_class(), $one);
        }
    }


    /**
     * Function permitted to search an entity by it "id".
     *
     * @param int $id Id of the entity.
     * @return mixed
     */
    public static function find($id, $idValue, $condition = "")
    {
        if ($condition) {
            return App::getDb()->prepare(
                "SELECT * FROM " . self::getTable() . " where " . $idValue . " = ? AND " . $condition,
                [$id],
                get_called_class(),
                true
            );
        } else {
            return App::getDb()->prepare(
                "SELECT * FROM " . self::getTable() . " WHERE " . $idValue . " = ?",
                [$id],
                get_called_class(),
                true
            );
        }
    }

    /**
     * This function get all fields in a table
     *
     * @return mixed
     */
    public static function all($condition = "")
    {
        if ($condition) {
            return App::getDb()->query(
                "SELECT *
                FROM " . self::getTable() . " WHERE " . $condition,
                null
            );
        } else {
            return App::getDb()->query(
                "SELECT *
                FROM " . self::getTable(),
                null
            );
        }
    }

    /**
     * This method is if we want to use methods like attributes of class
     * 
     */
    public function __get($key)
    {
        $method = "get" . ucfirst($key);
        $this->$key = $this->$method();

        return $this->$key;
    }

    /**
     * method to transform hashtag in a publication
     */
    public static function convertHashToColorText($string = "")
    {
        $expression = "/#+([a-zA-Z0-9_]+)/";
        return preg_replace($expression, '<strong class="siteColor">$0</strong>', $string);
    }
}
