<?php

use \SocialNetwork\app\lib\BaseController;

$router = new AltoRouter();


//Frontend User
$router->map('GET|POST', '/user/login/', '\SocialNetwork\app\controller\UserController#login');
$router->map('GET|POST', '/user/register/', '\SocialNetwork\app\controller\UserController#register');
$router->map('GET|POST', '/user/logout/', '\SocialNetwork\app\controller\UserController#logout');
$router->map('GET|POST', '/user/password/reset/', '\SocialNetwork\app\controller\UserController#passwordReset');
$router->map('GET|POST', '/user/password/reset/[a:hash]/', '\SocialNetwork\app\controller\UserController#passwordResetConfirmed');
$router->map('GET|POST', '/user/fblogin/', '\SocialNetwork\app\controller\UserController#fbcallback');
$router->map('GET|POST', '/user/glogin/', '\SocialNetwork\app\controller\UserController#gcallback');
$router->map('GET|POST', '/user/renew/auth/', '\SocialNetwork\app\controller\UserController#renewAuthCookie');
$router->map('GET|POST', '/my/settings/', '\SocialNetwork\app\controller\UserController#settings');


//Fronten STREAM

$router->map('GET|POST', '/public/stream/', '\SocialNetwork\app\controller\DataController#stream');

$router->map('GET|POST', '/stream/public', '\SocialNetwork\app\controller\DataController#stream');
$router->map('GET|POST', '/stream/private', '\SocialNetwork\app\controller\DataController#stream');
$router->map('GET|POST', '/stream/friends', '\SocialNetwork\app\controller\DataController#stream');

//Content API
$router->map('POST', '/api/content/', '\SocialNetwork\app\controller\DataController#post_content');
$router->map('POST', '/api/getmetainformation/', '\SocialNetwork\app\controller\DataController#getmeta');
$router->map('GET', '/api/content/', '\SocialNetwork\app\controller\DataController#content');
$router->map('GET', '/api/metadata/', '\SocialNetwork\app\controller\DataController#metadata');
$router->map('DELETE', '/api/content/[i:id]', '\SocialNetwork\app\controller\DataController#delete');
$router->map('PUT', '/api/content/[i:id]', '\SocialNetwork\app\controller\DataController#update');
$router->map('POST', '/api/content/report/[i:id]', '\SocialNetwork\app\controller\DataController#report');



//for autocomplete
$router->map('GET', '/api/hashtags/[a:auto]', '\SocialNetwork\app\controller\HashController#get');
$router->map('GET', '/api/users/[a:user]', '\SocialNetwork\app\controller\UserController#get');

// Api for Hashtag Scoring
$router->map('POST', '/api/hashtag/score/[a:hash]', '\SocialNetwork\app\controller\HashController#addScore');

//Comment API
$router->map('GET', '/api/comment/[i:id]', '\SocialNetwork\app\controller\CommentController#get_comment');
$router->map('POST', '/api/comment/[i:id]', '\SocialNetwork\app\controller\CommentController#post_comment');

//Score API
$router->map('POST', '/api/score/[a:type]/[i:id]', '\SocialNetwork\app\controller\ScoreController#post_score');
$router->map('GET', '/api/score/[i:id]', '\SocialNetwork\app\controller\ScoreController#get_score');

//Permalink and hash url
$router->map('GET', '/[page|permalink]/[i:id]', '\SocialNetwork\app\controller\DataController#get_permalink');
$router->map('GET', '/hash/[*:hash]', '\SocialNetwork\app\controller\DataController#get_hash');


$router->map('GET|POST', '/help/', '\SocialNetwork\app\controller\WebController#help');
$router->map('GET', '/resize/[*:img]', '\SocialNetwork\app\controller\WebController#resize'); 


$router->map("POST|GET", "/backend/", '\SocialNetwork\app\controller\BackendController#init');
$router->map("POST|GET", "/backend/[a:model]/delete/[i:id]/", '\SocialNetwork\app\controller\BackendController#delete');
$router->map("POST|GET", "/backend/[a:model]/create/", '\SocialNetwork\app\controller\BackendController#edit');
$router->map("POST|GET", "/backend/[a:model]/edit/[i:id]/", '\SocialNetwork\app\controller\BackendController#edit');
$router->map("POST|GET", "/backend/[a:model]/list/", '\SocialNetwork\app\controller\BackendController#table');
$router->map("POST|GET", "/backend/[a:model]/list/page/[i:page]/", '\SocialNetwork\app\controller\BackendController#table');
$router->map("POST|GET", "/backend/login/", '\SocialNetwork\app\controller\BackendController#login');
$router->map("POST|GET", "/backend/dashboard/", '\SocialNetwork\app\controller\DashboardController#dashboard');

$router->map("POST|GET", "/backend/dashboard/json/hashtags/", '\SocialNetwork\app\controller\DashboardController#dashboard_json_hashtags');
$router->map("POST|GET", "/backend/dashboard/json/content/", '\SocialNetwork\app\controller\DashboardController#dashboard_json_content');
$router->map("POST|GET", "/backend/dashboard/json/user/", '\SocialNetwork\app\controller\DashboardController#dashboard_json_user');
$router->map("POST|GET", "/backend/dashboard/user/", '\SocialNetwork\app\controller\DashboardController#dashboard_user');
$router->map("POST|GET", "/backend/dashboard/[a:target]/", '\SocialNetwork\app\controller\DashboardController#dashboard');
$router->map("GET", "/sitemap.xml", '\SocialNetwork\app\controller\WebController#sitemap');

$router->map('GET', '/[*:user]', '\SocialNetwork\app\controller\DataController#get_user');
$router->map('GET', '/', '\SocialNetwork\app\controller\DataController#stream');


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
    
    $error = new BaseController();
    $error->getResponse()->addStatusCode(402)->executeHeaders();
    $error->assign("title", "404");
    $error->render("404.php");
}
