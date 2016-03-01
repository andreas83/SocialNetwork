<?php


class CommentController extends BaseController
{

    /**
     * get_comment 
     * @param array $request[id]
     * @todo the foreach loop can be improved or removed at all
     * 
     * @param type $request
     */
    function get_comment($request) {
        $comment = new Comment();
        
        $data = $comment->getComment($request['id']);

        $i = 0;
        foreach ($data as $res) {
            $std[$i] = new stdClass();

            $std[$i]->text = $res->comment;
            $std[$i]->author = json_decode($res->settings);
            $std[$i]->author->name = $res->name;
            if (isset($std[$i]->author->profile_picture) && $std[$i]->author->profile_picture != 'null') {
                $std[$i]->author->profile_picture = Config::get('upload_address') . $std[$i]->author->profile_picture;
            }
            $i++;
        }

        $this->asJson(isset($std) ? $std : []);
    }
    
    /**
     * this function handles new comments
     * 
     */
    function post_comment($request){
        $comment = new Comment();
        if ($_POST && Helper::isUser()) {
            $comment->content_id = $request['id'];
            $comment->comment = $_POST['text'];
            $comment->user_id = Helper::getUserID();
            $comment->save();
            
            
            #start notification 
            $content= new Content;
            $content=$content->get($request['id']);
            
            $notification = new Notification;
            $notification->to_user_id=$content->user_id;
            $notification->from_user_id=Helper::getUserID();
            $notification->date=date("U");
            $notification->message='wrote something about your'
                    . ' <a href="/permalink/'.$request['id'].'">post</a>';
            if($notification->to_user_id!=Helper::getUserID())
                $notification->save();
                    
            
        } elseif ($_POST && !Helper::isUser()) {
            $this->getResponse()
                ->setHeaders('HTTP/1.0 403 Forbidden');

            return false;
        }
        
        $this->get_comment($request);

    }
}