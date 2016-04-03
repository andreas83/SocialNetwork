<?php

use app\lib\BaseController;

$router = new AltoRouter();


//Frontend User
$router->map('GET|POST', '/user/login/', '\app\controller\UserController#login');
$router->map('GET|POST', '/user/register/', '\app\controller\UserController#register');
$router->map('GET|POST', '/user/logout/', '\app\controller\UserController#logout');
$router->map('GET|POST', '/user/password/reset/', '\app\controller\UserController#passwordReset');
$router->map('GET|POST', '/user/password/reset/[a:hash]/', '\app\controller\UserController#passwordResetConfirmed');
$router->map('GET|POST', '/user/fblogin/', '\app\controller\UserController#fbcallback');
$router->map('GET|POST', '/user/glogin/', '\app\controller\UserController#gcallback');
$router->map('GET|POST', '/my/settings/', '\app\controller\UserController#settings');


//Fronten STREAM

$router->map('GET|POST', '/public/stream/', '\app\controller\DataController#stream');

$router->map('GET|POST', '/stream/public', '\app\controller\DataController#stream');
$router->map('GET|POST', '/stream/private', '\app\controller\DataController#stream');
$router->map('GET|POST', '/stream/friends', '\app\controller\DataController#stream');

//Content API
$router->map('POST', '/api/content/', '\app\controller\DataController#post_content');
$router->map('POST', '/api/getmetainformation/', '\app\controller\DataController#getmeta');
$router->map('GET', '/api/content/', '\app\controller\DataController#content');
$router->map('GET', '/api/metadata/', '\app\controller\DataController#metadata');
$router->map('DELETE', '/api/content/[i:id]', '\app\controller\DataController#delete');
$router->map('PUT', '/api/content/[i:id]', '\app\controller\DataController#update');
$router->map('POST', '/api/content/report/[i:id]', '\app\controller\DataController#report');



//for autocomplete
$router->map('GET', '/api/hashtags/[a:auto]', '\app\controller\HashController#get');
$router->map('GET', '/api/users/[a:user]', '\app\controller\UserController#get');

// Api for Hashtag Scoring
$router->map('POST', '/api/hashtag/score/[a:hash]', '\app\controller\HashController#addScore');

//Comment API
$router->map('GET', '/api/comment/[i:id]', '\app\controller\CommentController#get_comment');
$router->map('POST', '/api/comment/[i:id]', '\app\controller\CommentController#post_comment');

//Score API
$router->map('POST', '/api/score/[a:type]/[i:id]', '\app\controller\ScoreController#post_score');
$router->map('GET', '/api/score/[i:id]', '\app\controller\ScoreController#get_score');

//Permalink and hash url
$router->map('GET', '/[page|permalink]/[i:id]', '\app\controller\DataController#get_permalink');
$router->map('GET', '/hash/[*:hash]', '\app\controller\DataController#get_hash');


$router->map('GET|POST', '/help/', '\app\controller\WebController#help');



$router->map("POST|GET", "/backend/", '\app\controller\BackendController#init');
$router->map("POST|GET", "/backend/[a:model]/delete/[i:id]/", '\app\controller\BackendController#delete');
$router->map("POST|GET", "/backend/[a:model]/create/", '\app\controller\BackendController#edit');
$router->map("POST|GET", "/backend/[a:model]/edit/[i:id]/", '\app\controller\BackendController#edit');
$router->map("POST|GET", "/backend/[a:model]/list/", '\app\controller\BackendController#table');
$router->map("POST|GET", "/backend/[a:model]/list/page/[i:page]/", '\app\controller\BackendController#table');
$router->map("POST|GET", "/backend/login/", '\app\controller\BackendController#login');
$router->map("POST|GET", "/backend/dashboard/", '\app\controller\DashboardController#dashboard');

$router->map("POST|GET", "/backend/dashboard/json/hashtags/", '\app\controller\DashboardController#dashboard_json_hashtags');
$router->map("POST|GET", "/backend/dashboard/json/content/", '\app\controller\DashboardController#dashboard_json_content');
$router->map("POST|GET", "/backend/dashboard/json/user/", '\app\controller\DashboardController#dashboard_json_user');
$router->map("POST|GET", "/backend/dashboard/user/", '\app\controller\DashboardController#dashboard_user');
$router->map("POST|GET", "/backend/dashboard/[a:target]/", '\app\controller\DashboardController#dashboard');
$router->map("GET", "/sitemap.xml", '\app\controller\WebController#sitemap');

$router->map('GET', '/[*:user]', '\app\controller\DataController#get_user');
$router->map('GET', '/', '\app\controller\DataController#stream');


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
