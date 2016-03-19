<?php

/**
 * Class WebController
 */
class WebController extends BaseController {

    

    function sitemap() {
        $data= new Content();
        $res=$data->getAll();
        $this->assign("data", $res);
        
        $this->render("sitemap.php");
    }
    
    function help(){
        $user= new User;
        if(Helper::isUser())
        {
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
}