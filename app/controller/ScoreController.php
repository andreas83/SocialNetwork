<?php
namespace SocialNetwork\app\controller;

use SocialNetwork\app\lib\Helper;
use SocialNetwork\app\model\Content;
use SocialNetwork\app\model\Notification;
use SocialNetwork\app\model\Score;

/**
 * Class ScoreController
 */
class ScoreController
{
    
    /**
     * get like/dislixxgddgke information for 
     * content element
     * 
     * @todo make one query for like/dislike
     * @param array $request['id']
     */
    function get_score($request)
    {
        $score = new Score();
        
        $like=$score->find(array(
            "content_id"=>$request['id'], 
            "type" => "add"));
        
        $dislike=$score->find(array(
            "content_id"=>$request['id'], 
            "type" => "sub"));
        
        
        if (count($like)>0 || count($dislike)>0) {
            echo json_encode(array("like"=>count($like), "dislike"=>count($dislike)));
        } else {
            echo json_encode(array("like" =>0, "dislike" => 0));
        }
    }
    
    /**
     * save like/dislike
     * 
     * @return void|bool
     */
    function post_score($request)
    {
        
        $score = new Score();
        if (isset($request['type']) && Helper::isUser()) {
            
            $find=$score->find(array(
                "content_id"=>$request['id'], 
                "user_id" => Helper::getUserID(),
                "type" => $request['type']));
            
            if(count($find)>0)
            {
                $find[0]->delete($find[0]->id);

                $this->get_score($request);
                
                return null;
            }
            
            $score->user_id = Helper::getUserID();
            $score->content_id = $request['id'];
            $score->type = $request['type'];
            $score->save();
            
            $content= new Content;
            $content=$content->get($request['id']);
            
            $notification = new Notification;
            $notification->to_user_id=$content->user_id;
            $notification->from_user_id=Helper::getUserID();
            $notification->date=date("U");
            $notification->message='scored your'
                    . ' <a href="/permalink/'.$request['id'].'">post</a>';
            if($content->user_id!=Helper::getUserID())
                 $notification->save();
           
            
        } elseif ($_POST && !Helper::isUser()) {
            header('HTTP/1.0 403 Forbidden');
            return false;
        }
        
        $this->get_score($request);
    }
}
