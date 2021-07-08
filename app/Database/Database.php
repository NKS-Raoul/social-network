<?php

namespace App\Database;

use \PDO; // for PDO using...

/**
 * this class facilitate the rapid and good use of requests ...
 */
class Database
{
    private $db_user; // the name of database user
    private $db_pass; // the password of database
    private $db_host; // host of database
    private $db_name; // the name of database
    private $pdo; // this variable is for manipulation, will take that object.

    /**
     * Creating a new instance of the database.
     *
     * @param string $db_name database name
     * @param string $db_user name of the database user
     * @param string $db_pass the password of database user
     * @param string $db_host host of database
     */
    public function __construct($db_name = "forum", $db_user = "root", $db_pass = "", $db_host = "localhost")
    {
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_host = $db_host;
    }

    /**
     * Getting instance of PDO and adding our configuration
     *
     * @return PDO
     */
    public function getPDO()
    {
        if ($this->pdo == null) {
            $pdo1 = new PDO('mysql:dbname=' . $this->db_name . ";host=" . $this->db_host, $this->db_user, $this->db_pass);
            $pdo1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->pdo = $pdo1;
        }

        return $this->pdo;
    }

    /**
     * For cleaning manipulation, we rewrite the query method
     * 
     * In a full Oriented Object Programming, we will get some information in form of class
     * 
     * The query method will take tree variables : 
     *      - $statement : which is the request
     *      - $class_name : which represent the name of concern class
     *          . If the class is null, we will make a fetch in object
     *          . If the class is define, we will make fetch according to that class
     *      - %one : which allow to define if we want un or more result
     */
    public function query($statement, $class_name, $one = false)
    {
        $req = $this->getPDO()->query($statement);

        if ($class_name == null) {
            $req->setFetchMode(PDO::FETCH_OBJ);
        } else {
            $req->setFetchMode(PDO::FETCH_CLASS, $class_name);
        }

        if ($one) {
            $data = $req->fetch();
        } else {
            $data = $req->fetchAll();
        }

        return $data;
    }

    /**
     * The prepare request method has the same function with query
     * with small different, this one take an array of elements and make execution
     * 
     * @param string $statement sql_request which is the request to make
     * @param array $attributes arrays which is the Array of elements to make request
     * @param string $class_name class which is the Array of elements to make request
     * @param bool $one bool which allow to define if we want un or more result 
     * 
     * @return array|object Array of elements
     */
    public function prepare($statement, $attributes, $class_name, $one = false)
    {
        $req = $this->getPDO()->prepare($statement);
        $req->execute($attributes);
        if ($class_name == null) {
            $req->setFetchMode(PDO::FETCH_OBJ);
        } else {
            $req->setFetchMode(PDO::FETCH_CLASS, $class_name);
        }

        if ($one) {
            $data = $req->fetch();
        } else {
            $data = $req->fetchAll();
        }

        return $data;
    }
}
