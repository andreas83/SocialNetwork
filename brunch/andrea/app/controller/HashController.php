<?php

/**
 * Class Hashcontroller
 */
class HashController extends BaseController {
    
    function get($request){
        $hash= new Hashtags;
        
        $res=$hash->findHashtags($request['auto']);
        
        header('Content-Type: application/json');
        echo json_encode($res);
        
    }
}

