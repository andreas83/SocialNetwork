<?php
// ATTENTION !!!!
// please include only files if you know how the
// autoloader works, usealy there is no need for that
include_once("app/lib/AutoLoader.php");
include_once("routing.php");
if(isset($_SESSION['user_settings']))
    $user_settings = json_decode($_SESSION['user_settings']);