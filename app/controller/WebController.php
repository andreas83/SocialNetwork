<?php
namespace SocialNetwork\app\controller;
use SocialNetwork\app\lib\Config;
use SocialNetwork\app\lib\BaseController;
use SocialNetwork\app\lib\Helper;
use SocialNetwork\app\model\Content;
use SocialNetwork\app\model\Hashtags;
use SocialNetwork\app\model\User;

use Intervention\Image\ImageManager;

/**
 * Class WebController
 */
class WebController extends BaseController
{

    function sitemap()
    {
        $data= new Content();
        $res=$data->getAll();
        $this->assign("data", $res);
        
        $this->render("sitemap.php");
    }
    
    function help()
    {
        $user= new User();

        if(Helper::isUser()) {
            $user=$user->get(Helper::getUserID());
            $this->assign("api_key", $user->api_key);
        }
        
        $hashtags= new Hashtags;
      
        $this->assign("popularhashtags",   $hashtags->getPopularHashtags());
        $this->assign("randomhashtags",   $hashtags->getRandomHashtags());
        
        $this->assign("title", "Social Network - API Documentation");
        $this->assign("keyword", "json, rest, api, documentation, ajax, javascript, jquery, crud, post, put, get, delete");
        $this->assign("description", "Examples about adding and modifing content programmatically");
        $this->addFooter("<script> hljs.initHighlightingOnLoad(); </script>");
        $this->render("help.php");
    }

    function resize($res){
        $file=Config::get("dir") . Config::get("upload_path").$res['img'];
        if(file_exists($file))
        {
		$manager = new ImageManager(array('driver' => 'gd'));
		echo $manager->make($file)->widen(500, function ($constraint) {
   			 $constraint->upsize();
   			 $constraint->aspectRatio();
		})->response("png", 70);

        }

    }
}
