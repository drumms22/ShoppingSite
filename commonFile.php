<?php

/**
 * @file
 * Common functions shared by every page.
 */



// include('../../connect.php');
session_start();

$host = "localhost";
$database = "api_test";
$username = "root";
$password = "";

spl_autoload_register(function ($class_name) {
    include __DIR__ . '/api/Services/' . $class_name . '.php';
});

$dsn = "mysql:host=" . $host . ";dbname=" . $database . ";charset=utf8mb4;";

$driver_options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
];

$pdo = new PDO($dsn, $username, $password);

$container = Container::create($pdo);

include("routeHandler.php");

if(isset($post_results['login'])){

  if(count($post_results['login']['errors']) == 0){

    $_SESSION['current_user'] = $post_results['login']['results'];
    unset($_SESSION['guest_user']);
    setcookie("guest_user", "", time() - 3600);

  }

}

if(!isset($_SESSION['current_user']) && !isset($_SESSION['guest_user']) && !isset($_COOKIE['guest_user'])){

  $id = bin2hex(random_bytes(64));

  $_SESSION['guest_user'] = $id;
  $expire = time()+60*60*24*30;
  setcookie("guest_user", $id, $expire);

}else if(isset($_COOKIE['guest_user']) && !isset($_SESSION['current_user']) && !isset($_SESSION['guest_user'])){

  $_SESSION['guest_user'] = $_COOKIE['guest_user'];

}


