<?php
namespace SocialNetwork\app\controller;

use SocialNetwork\app\lib\BaseController;
use SocialNetwork\app\lib\Config;
use SocialNetwork\app\lib\Helper;
use SocialNetwork\app\model\Comment;
use SocialNetwork\app\model\Content;
use SocialNetwork\app\model\Notification;
use SocialNetwork\app\model\User;
use WebSocket\Client;

/**
 * Class CommentController
 * @package SocialNetwork\app\controller
 */
class CommentController extends BaseController
{

    /**
     * get_comment
     * @param array $request[id]
     * @todo the foreach loop can be improved or removed at all
     *
     * @param type $request
     */
    public function get_comment($request)
    {
        $comment = new Comment();
        
        $data = $comment->getComment($request['id']);

        $i = 0;
        foreach ($data as $res) {
            $std[$i] = new \stdClass();

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
    public function post_comment($request)
    {
        $comment = new Comment();

        if ($_POST && Helper::isUser()) {
            $comment->content_id = $request['id'];
            $comment->comment = $_POST['text'];
            $comment->user_id = Helper::getUserID();
            $comment->save();
            
            $client = new Client(Config::get("notification_server"));
            
            #start notification for post owner
            $content= new Content;
            $content=$content->get($request['id']);
            
            $notification = new Notification;
            $notification->to_user_id=$content->user_id;
            $notification->from_user_id=Helper::getUserID();
            $notification->date=date("U");
            $notification->message='wrote something about your'
                    . ' <a href="/permalink/'.$request['id'].'">post</a>';

            if ($notification->to_user_id!=Helper::getUserID()) {
                $notification->save();
            }
            
            $client->send(json_encode(array("action"=>"update", "uid" =>$notification->to_user_id)));
            
            #notification for @users mention
            $pattern="/(^|\s)@(\w*[a-zA-Z0-9öäü._-]+\w*)/";
            preg_match_all($pattern, $comment->comment, $users);
            if (count($users[0])>0) {
                $user = new User;
                
                $notification->from_user_id=Helper::getUserID();
                $notification->date=date("U");
                $notification->message='mention you in a '
                        . '<a href="/permalink/'.$request['id'].'">comment</a>';
                
                foreach ($users[0] as $username) {
                    $username = trim(str_replace(array("@", "."), " ", $username));
                    $res = $user->find(array("name" => $username));
                    
                    $notification->to_user_id=$res[0]->id;
                    
                    if ($notification->to_user_id!=Helper::getUserID()) {
                        $notification->save();
                    }
                    
                    //we notifiy the socket server about an update
                    $client->send(json_encode(array("action"=>"update", "uid" =>$notification->to_user_id)));
                }
            }
        } elseif ($_POST && !Helper::isUser()) {
            $this->getResponse()
                ->setHeaders('HTTP/1.0 403 Forbidden');

            return false;
        }
        
        $this->get_comment($request);

        return true;
    }
}
