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
    
    function addScore($request){
        $hash= new Hashtags;
        
        $res=$hash->findHashtags($request['hash']);
        
        $res[0]->pop=$res[0]->pop+1;
        $res[0]->save();
        
        header('Content-Type: application/json');
        echo json_encode($res);
        
    }
}

