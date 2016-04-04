<?php
namespace SocialNetwork\app\server;
include_once("vendor/autoload.php");



use SocialNetwork\app\lib\Config;
use SocialNetwork\app\lib\WebSocketServer;
use SocialNetwork\app\model\Notification;
use SocialNetwork\app\model\User;



class notificationServer extends WebSocketServer
{

    /**
     * @var int
     */
    protected $maxBufferSize = 1048576;

    /**
     * @var array
     */
    private $activeUser = [];

    /**
     * @var array
     */
    private $channel = [];

    /**
     * @param $user
     * @param $message
     */
    protected function process($user, $message)
    {

        $data = json_decode($message);

        if ($data->action == "getNotifications" && $data->auth_cookie != "") {
            $notifications = new Notification();
            $res = $notifications->getNotificationsByCookie($data->auth_cookie);

            $userObj = new User();
            $userObj = $userObj->find(array("auth_cookie" => $data->auth_cookie));

            $user->uid = $userObj[0]->id;

            $this->send($user, json_encode(array("notificaton" => $res)));
        }

        if ($data->action == "openroom" && $data->auth_cookie != "") {
            $userObj = new User;
            $res = $userObj->find(array("auth_cookie" => $data->auth_cookie));
            $username = str_replace(" ", ".", $res[0]->name);
            $settings = json_decode($res[0]->settings);
            $profile_img = "";

            if ($settings->profile_picture != "") {
                $img_url = Config::get("upload_address") . $settings->profile_picture;
                $profile_img = "<img src=\"$img_url\" />&nbsp;";
            }

            $this->activeUser[$user->id] = '<a href="/' . $username . '">' . $profile_img . $res[0]->name . '</a>';

            $this->send($user, json_encode(array("activeUsers" => $this->activeUser, "channel" => $this->channel)));
        }

        if ($data->action == "openroom" && $data->auth_cookie == "") {

            $this->activeUser[$user->id] = "Anonymous - " . uniqid();

            $this->send($user, json_encode(array("activeUsers" => $this->activeUser, "channel" => $this->channel)));
            $this->updateUsers();
        }

        if ($data->action == "chat") {
            $userObj = new User;

            if ($data->auth_cookie == ""){
                $res = $userObj->find(array("id" => 1));
            } else {
                $res = $userObj->find(array("auth_cookie" => $data->auth_cookie));
            }

            //save notification for mentions @username
            $pattern = "/(^|\s)@(\w*[a-zA-Z0-9öäü._-]+\w*)/";
            preg_match_all($pattern, $data->text, $users);

            if (count($users[0]) > 0) {

                $notification = new Notification;
                $notification->from_user_id = $res[0]->id;
                $notification->date = date("U");
                $notification->message = 'mention you in chat';
                $user_ids = array();
                
                foreach ($users[0] as $username) {
                    $username = trim(str_replace(array("@", "."), " ", $username));
                    $res = $userObj->find(array("name" => $username));
                    if (count($res) > 0) {
                        $notification->to_user_id = $res[0]->id;
                        $notification->save();
                        $user_ids[] = $notification->to_user_id;
                    }
                }

                foreach ($this->users as $activeuser) {
                    if (in_array($activeuser->uid, $user_ids)) {

                        $notifications = new Notification;
                        $res = $notifications->getNotificationsByID($activeuser->uid);
                        $this->send($activeuser, json_encode(array("notificaton" => $res)));
                    }
                }
            }

            $this->channel["default"][] = html_entity_decode($this->activeUser[$user->id] . ": " . strip_tags($data->text, "<b><a>"));

            $this->send($user, json_encode(array("activeUsers" => $this->activeUser, "channel" => $this->channel)));
            $this->updateUsers();
        }


        if ($data->action == "update" && $data->uid > 0) {
            //here we just update given user
            $this->send($user, "close");
            foreach ($this->users as $user) {
                if ($user->uid == $data->uid) {
                    $notifications = new Notification;
                    $res = $notifications->getNotificationsByID($data->uid);
                    $this->send($user, json_encode(array("notificaton" => $res)));
                }
            }

        }
    }

    function updateUsers()
    {
        foreach ($this->users as $user) {
            $this->send($user, json_encode(array("activeUsers" => $this->activeUser, "channel" => $this->channel)));
        }
    }

    /**
     * ???
     * @param $user
     */
    protected function connected($user)
    {

        $notifications = new Notification;
        //remove some old notifications first aka garbage collection
        $notifications->cleanup();
    }


    /**
     * @param $user
     */
    protected function closed($user)
    {
        // Do nothing: This is where cleanup would go, in case the user had any sort of
        // open files or other objects associated with them.  This runs after the socket
        // has been closed, so there is no need to clean up the socket itself here.
        unset($this->activeUser[$user->id]);

        $this->updateUsers();

        echo "closed";
    }
}

$newsserver = new notificationServer("0.0.0.0", "9000");

try {
    $newsserver->run();
} catch (\Exception $e) {
    $newsserver->stdout($e->getMessage());
}