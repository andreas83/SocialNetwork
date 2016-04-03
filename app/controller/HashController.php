<?php
namespace app\controller;

use app\lib\BaseController;
use app\model\Hashtags;

/**
 * Class Hashcontroller
 */
class HashController extends BaseController
{

    /**
     * @param $request
     */
    function get($request)
    {
        $hash= new Hashtags;
        
        $res=$hash->findHashtags($request['auto']);

        $this->asJson($res);
    }

    /**
     * @param $request
     */
    function addScore($request)
    {
        $hash= new Hashtags;
        
        $res=$hash->find(array("hashtag" => $request['hash']));
        
        $res[0]->pop=$res[0]->pop+1;
        $res[0]->save();

        $this->asJson($res);
    }
}

