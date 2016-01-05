<?php

class Notification extends BaseModel
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
    
    public function getBackendConfiguration(){
        $backend = new ConfigureBackend;
        $backend->setEditable(array("id", "to_user_id", "from_user_id", "message"));
        $backend->setVisible(array("id", "to_user_id",  "from_user_id", "date"));
        $backend->setSearchable(array("id", "to_user_id",  "from_user_id", "date"));
        return $backend;
        
    }
    
    public function cleanup(){
        $enddate=  strtotime("-2 days");
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
                . "Notification.from_user_id = fromUser.id order by date desc";
        
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':auth_cookie', $auth_cookie, PDO::PARAM_STR);

        $stmt->execute();

        $obj = $stmt->fetchALL(PDO::FETCH_CLASS, "stdClass");

        return $obj;
        
    
    }
}
?>
