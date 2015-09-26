<?php

$router = new AltoRouter();


//Frontend User
$router->map('GET|POST', '/user/login/', 'UserController#login');
$router->map('GET|POST', '/user/register/', 'UserController#register');
$router->map('GET|POST', '/user/logout/', 'UserController#logout');
$router->map('GET|POST', '/my/settings/', 'UserController#settings');


//Fronten STREAM

$router->map('GET|POST', '/public/stream/', 'DataController#stream');

$router->map('GET|POST', '/stream/public', 'DataController#stream');
$router->map('GET|POST', '/stream/private', 'DataController#stream');
$router->map('GET|POST', '/stream/friends', 'DataController#stream');

//Content API
$router->map('POST', '/api/create/', 'DataController#create');
$router->map('POST', '/api/getmetainformation/', 'DataController#getmeta');
$router->map('GET', '/api/content/', 'DataController#content');
$router->map('GET', '/api/metadata/', 'DataController#metadata');
$router->map('DELETE', '/api/content/[i:id]', 'DataController#delete');
$router->map('PUT', '/api/content/[i:id]', 'DataController#update');

//for autocomplete
$router->map('GET', '/api/hashtags/[a:auto]', 'HashController#get');


//Comment API
$router->map('GET|POST', '/api/comments/[i:id]', 'DataController#comment');

//Score API
$router->map('POST|GET', '/api/score/[a:type]/[i:id]', 'DataController#score');
$router->map('GET|POST', '/api/score/[i:id]', 'DataController#score');



$router->map('GET|POST', '/', 'DataController#frontend');


$match = $router->match();
if ($match) {

    //backend -> redirect to login
    if (!isset($_SESSION['isAdmin']) && strpos($_SERVER['REQUEST_URI'], "/backend/") === 0) {
        #var_dump(strpos($_SERVER['REQUEST_URI'], "/backend/login/"));

        if (strpos($_SERVER['REQUEST_URI'], "/backend/login/") === false) {
            header("Location: /backend/login/");
        }
    }


    //check if its a static file
    if (is_file("app/template/" . $match['target'])) {
        include_once("app/template/" . $match['target']);
    }


    if (strpos($match['target'], "#")) {
        list($object, $method) = explode("#", $match['target']);
        $view = new $object;
        $view->$method($match['params']);
    }

} else {
    header("HTTP/1.0 404.php Not Found");
    $error = new BaseController();
    $error->assign("title", "404");
    $error->render("404.php");
}
