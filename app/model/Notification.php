<?php

class Notification extends BaseApp
{

    public $id = "";
    public $user_id = "";

    public $message ="";
    public $level ="";
    public $date = "";
    

    public function getPrimary()
    {
        return "id";
    }
    
    public function cleanup(){
        $enddate=  strtotime("-1 week");
        $sql = "delete from Notification where date<:enddata";
        
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':enddate', $enddate, PDO::PARAM_INT);

        $stmt->execute();
    }
    
    public function getNotifications($auth_cookie, $level=false) {
        
        $sql = "select * from Notification "
                . "inner join User on Notification.user_id=User.id and "
                . "User.auth_cookie=:auth_cookie "
                . "order by Notification.date desc";
        
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':auth_cookie', $auth_cookie, PDO::PARAM_STR);

        $stmt->execute();

        $obj = $stmt->fetchALL(PDO::FETCH_CLASS, "stdClass");

        return $obj;
        
    
    }
}
?>
