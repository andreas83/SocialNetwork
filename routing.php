<?php

$router = new AltoRouter();


//Frontend User
$router->map('GET|POST', '/user/login/', 'UserController#login');
$router->map('GET|POST', '/user/register/', 'UserController#register');
$router->map('GET|POST', '/user/logout/', 'UserController#logout');
$router->map('GET|POST', '/user/password/reset/', 'UserController#passwordReset');
$router->map('GET|POST', '/user/password/reset/[a:hash]/', 'UserController#passwordResetConfirmed');
$router->map('GET|POST', '/user/fblogin/', 'UserController#fbcallback');
$router->map('GET|POST', '/user/glogin/', 'UserController#gcallback');
$router->map('GET|POST', '/my/settings/', 'UserController#settings');


//Fronten STREAM

$router->map('GET|POST', '/public/stream/', 'DataController#stream');

$router->map('GET|POST', '/stream/public', 'DataController#stream');
$router->map('GET|POST', '/stream/private', 'DataController#stream');
$router->map('GET|POST', '/stream/friends', 'DataController#stream');

//Content API
$router->map('POST', '/api/content/', 'DataController#post_content');
$router->map('POST', '/api/getmetainformation/', 'DataController#getmeta');
$router->map('GET', '/api/content/', 'DataController#content');
$router->map('GET', '/api/metadata/', 'DataController#metadata');
$router->map('DELETE', '/api/content/[i:id]', 'DataController#delete');
$router->map('PUT', '/api/content/[i:id]', 'DataController#update');
$router->map('POST', '/api/content/report/[i:id]', 'DataController#report');



//for autocomplete
$router->map('GET', '/api/hashtags/[a:auto]', 'HashController#get');
$router->map('GET', '/api/users/[a:user]', 'UserController#get');

// Api for Hashtag Scoring
$router->map('POST', '/api/hashtag/score/[a:hash]', 'HashController#addScore');

//Comment API
$router->map('GET', '/api/comment/[i:id]', 'CommentController#get_comment');
$router->map('POST', '/api/comment/[i:id]', 'CommentController#post_comment');

//Score API
$router->map('POST', '/api/score/[a:type]/[i:id]', 'ScoreController#post_score');
$router->map('GET', '/api/score/[i:id]', 'ScoreController#get_score');

//Permalink and hash url
$router->map('GET', '/[page|permalink]/[i:id]', 'DataController#get_permalink');
$router->map('GET', '/hash/[*:hash]', 'DataController#get_hash');


$router->map('GET|POST', '/help/', 'WebController#help');



$router->map("POST|GET", "/backend/", "BackendController#init");
$router->map("POST|GET", "/backend/[a:model]/delete/[i:id]/", "BackendController#delete");
$router->map("POST|GET", "/backend/[a:model]/create/", "BackendController#edit");
$router->map("POST|GET", "/backend/[a:model]/edit/[i:id]/", "BackendController#edit");
$router->map("POST|GET", "/backend/[a:model]/list/", "BackendController#table");
$router->map("POST|GET", "/backend/[a:model]/list/page/[i:page]/", "BackendController#table");
$router->map("POST|GET", "/backend/login/", "BackendController#login");
$router->map("POST|GET", "/backend/dashboard/", "DashboardController#dashboard");

$router->map("POST|GET", "/backend/dashboard/json/hashtags/", "DashboardController#dashboard_json_hashtags");
$router->map("POST|GET", "/backend/dashboard/json/content/", "DashboardController#dashboard_json_content");
$router->map("POST|GET", "/backend/dashboard/json/user/", "DashboardController#dashboard_json_user");
$router->map("POST|GET", "/backend/dashboard/user/", "DashboardController#dashboard_user");
$router->map("POST|GET", "/backend/dashboard/[a:target]/", "DashboardController#dashboard");
$router->map("GET", "/sitemap.xml", "WebController#sitemap");

$router->map('GET', '/[*:user]', 'DataController#get_user');
$router->map('GET', '/', 'DataController#stream');


$match = $router->match();
if ($match) {

    //backend -> redirect to login
    if (!isset($_SESSION['isAdmin']) && strpos($_SERVER['REQUEST_URI'], "/backend/") === 0) {
        
                
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
