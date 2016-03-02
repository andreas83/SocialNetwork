<?php

require_once 'app/lib/AutoLoader.php';


class notificationServer extends WebSocketServer {
  //protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.
  
  protected function process ($user, $message) {
    
      $data=  json_decode($message);
      if($data->action == "getNotifications" && $data->auth_cookie!="")
      {
          $notifications = new Notification;
          $res=$notifications->getNotifications($data->auth_cookie);
          $this->send($user, json_encode($res));
      }
      
  }
  
  protected function connected ($user) {
     
      $notifications = new Notification;
      //remove some old notifications first aka garbage collection
      $notifications->cleanup();
  }
  
  
  
  protected function closed ($user) {
    // Do nothing: This is where cleanup would go, in case the user had any sort of
    // open files or other objects associated with them.  This runs after the socket 
    // has been closed, so there is no need to clean up the socket itself here.
      
      echo $user->uid."closed";
      
  }
}

$newsserver = new notificationServer("0.0.0.0","9000");

try {
  $newsserver->run();
  
}
catch (Exception $e) {
  $newsserver->stdout($e->getMessage());
}