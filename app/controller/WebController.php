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
}