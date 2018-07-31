<?php
/**
 * Created by PhpStorm.
 * User: Yushi
 * Date: 28.07.2018
 * Time: 2:29
 */
//error_reporting(E_ERROR  | E_PARSE );

session_start();
define('ROOT', dirname(__FILE__));

require_once "router.php";
require_once "dbconnection.php";

$router = new router();
$router->run();