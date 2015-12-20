<?php

class ScoreController{
    
    /**
     * get like/dislike information for 
     * content element
     * 
     * @todo make one query for like/dislike
     * @param array $request['id']
     */
    function get_score($request) {

        
        $score = new Score();
        
        $like=$score->find(array(
            "content_id"=>$request['id'], 
            "type" => "add"));
        
        $dislike=$score->find(array(
            "content_id"=>$request['id'], 
            "type" => "sub"));
        
        header('Content-Type: application/json');
   
        if (count($like)>0 || count($dislike)>0)
            echo json_encode(array("like"=>count($like), "dislike"=>count($dislike)));
        else {
            echo json_encode(array("like" =>0, "dislike" => 0));
        }
    }
    
    /**
     * save like/dislike
     * 
     * @return void
     */
    function post_score($request){
        $score = new Score();
        if (isset($request['type']) && Helper::isUser()) {
            
            $find=$score->find(array(
                "content_id"=>$request['id'], 
                "user_id" => $_SESSION['login'],
                "type" => $request['type']));
            
            if(count($find)>0)
            {
                $find[0]->delete($find[0]->id);
                return $this->get_score($request);
                    
            }
            
            $score->user_id = $_SESSION['login'];
            $score->content_id = $request['id'];
            $score->type = $request['type'];
            $score->save();
            
            $content= new Content;
            $content=$content->get($request['id']);
            
            $notification = new Notification;
            $notification->to_user_id=$content->user_id;
            $notification->from_user_id=$_SESSION['login'];
            $notification->date=date("U");
            $notification->message='scored your'
                    . ' <a href="/permalink/'.$request['id'].'">post</a>';
            if($content->user_id!=$_SESSION['login'])
                $notification->save();
            
            
        } elseif ($_POST && !Helper::isUser()) {
            header('HTTP/1.0 403 Forbidden');
            return false;
        }
        
        $this->get_score($request);
    }
}