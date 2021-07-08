<?php

namespace App;


/**
 * This is an autoload and it walk like this :
 *      -The namespaces are set by folders, this means that the program will get the namespace if it does not 
 *          look the class, search the class into all the folders. 
 * 
 *      -The constant __CLASS__ is used to return the name of class which is using now. 
 *      -The constant __NAMESPACE__ allow to return the name of namespace using now during the class calling
 */
class Autoloader{


    static function session_start(){
        session_start(); // To avoid the error of session! the session is open and it will be executed at the moment without duplication
        spl_autoload_register(array(__CLASS__, "autoload"));
    }

    /**
     * Static method which is permit to make the automatic load of classes
     *
     * @param string $class
     */
    static function autoload($class){
        if(strpos($class, __NAMESPACE__. "\\") === 0){
            $class = str_replace(__NAMESPACE__."\\", "", $class);
            $class = str_replace("\\", "/", $class);
            
            require __DIR__."/".$class.".php";
        }
    }
}