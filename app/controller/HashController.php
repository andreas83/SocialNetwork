<?php

/**
 * Class Hashcontroller
 */
class HashController extends BaseController
{
    
    function get($request)
    {
        $hash= new Hashtags;
        
        $res=$hash->findHashtags($request['auto']);

        $this->asJson($res);
    }
    
    function addScore($request)
    {
        $hash= new Hashtags;
        
        $res=$hash->find(array("hashtag" => $request['hash']));
        
        $res[0]->pop=$res[0]->pop+1;
        $res[0]->save();

        $this->asJson($res);
    }
}

