<?php

namespace App;

require_once('app/Autoloader.php');
Autoloader::session_start();

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = "home";
}

$road = new Routeur($page);
$road->renderController();


