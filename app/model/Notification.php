<?php

class Notification extends BaseApp
{

    public $id = "";
    public $to_user_id = "";
    public $from_user_id = "";

    public $message ="";
    public $level ="";
    public $date = "";
    

    public function getPrimary()
    {
        return "id";
    }
    
    public function cleanup(){
        $enddate=  strtotime("-1 week");
        $sql = "delete from Notification where date<:enddate";
        
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':enddate', $enddate, PDO::PARAM_INT);

        $stmt->execute();
    }
    
    public function getNotifications($auth_cookie) {
        
        $sql = "select Notification.*, "
                . "fromUser.* from Notification"
                . " inner join User on "
                . "Notification.to_user_id=User.id and "
                . "User.auth_cookie=:auth_cookie "
                . " left join User fromUser on "
                . "Notification.from_user_id = fromUser.id";
        
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':auth_cookie', $auth_cookie, PDO::PARAM_STR);

        $stmt->execute();

        $obj = $stmt->fetchALL(PDO::FETCH_CLASS, "stdClass");

        return $obj;
        
    
    }
}
?>
