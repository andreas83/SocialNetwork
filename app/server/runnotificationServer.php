<?php

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

        echo "closed";
    }
}

$newsserver = new notificationServer("0.0.0.0", "9000");

try {
    $newsserver->run();
} catch (\Exception $e) {
    $newsserver->stdout($e->getMessage());
}