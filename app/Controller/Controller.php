<?php

namespace App\Controller;

use App\App;
use App\Auth\DBAuth;

/**
 * The principal controller the others will inherit
 */
class Controller
{

    // these attributes will make dynamic the link to others pages or inclusions

    /** standard root */
    public static $root;
    /** host like "http://localhost/ */
    public static $host;
    /** for the views path : from the root to the views folder */
    public static $viewPath;
    /** for the controller path : from the root to the controller folder */
    public static $controllerPath;
    /** for the assets path : from the host to the assets folder */
    public static $assetsPath;
    /** for the assets path : from the host to the files folder */
    public static $filesPath;
    /** for the assets path : from the document root to the files folder */
    public static $filesRootPath;
    /** for the vendor path :  */
    public static $vendorPath;
    /** @var DBAuth authentication object */
    public static $authObj;

    public function __construct()
    {
        self::$root = $_SERVER['DOCUMENT_ROOT'] . "/forum/";
        self::$host = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/forum/";
        self::$viewPath = self::$root . "app/Views/";
        self::$controllerPath = self::$root . "controller/";
        self::$assetsPath = self::$host . "public/assets/";
        self::$filesPath = self::$host . "public/files/";
        self::$filesRootPath = self::$root . "public/files/";
        self::$authObj = new DBAuth(App::getDb());
    }

    /**
     * Function permitted to convert special character to HTML entities
     * It is "protected" for children of class to access this !
     * A secure measure from XSS injection 
     *
     * @param string $string string to convert
     * @return string
     */
    protected function e($string)
    {
        return htmlspecialchars($string);
    }

    /**
     * This method is used to generate the views
     * The key of associative array will be transform into variables
     * 
     * @param array $params the array of element that will be use in views
     */
    public function render($params = array())
    {

        extract($params);

        $_SESSION['error'] = null; // For login errors 
        $_SESSION['errors'] = null; // Tables of errors

        // inclusion and redirection variables
        $assets = self::$assetsPath;
        $files = self::$filesPath;
        $controller = self::$controllerPath;
        $view = self::$viewPath;
        $host = self::$host;
        $root = self::$root;

        // Authentication object
        $authObj = self::$authObj;

        // sleep(5);
        // The view is get, and put into a variable for displaying
        ob_start();
        include_once(self::$viewPath . $vue . ".view.php");
        $contentPage = ob_get_clean();
        include_once(self::$viewPath . "templates/index.php");
    }


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
            } elseif ($i == 0 && $diff < 4) {
                $format = "Now";
            } else {
                $format = $diff . " " . $strTime[$i] . ($diff == 1 ? "" : "s");
            }

            return $format;
        }
    }
}
