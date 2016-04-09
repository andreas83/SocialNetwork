<?php
namespace SocialNetwork\app\model;

use SocialNetwork\app\lib\BaseModel;
use SocialNetwork\app\lib\ConfigureBackend;

class Notification extends BaseModel
{

    public $id = "";
    public $to_user_id = "";
    public $from_user_id = "";

    public $message ="";
    public $level ="";
    public $date = "";


    /**
     * @return string
     */
    public function getSource()
    {
        return 'Notification';
    }

    /**
     * @return string
     */
    public function getPrimary()
    {
        return "id";
    }

    /**
     * @return ConfigureBackend
     */
    public function getBackendConfiguration()
    {
        $backend = new ConfigureBackend;
        $backend->setEditable(array("id", "to_user_id", "from_user_id", "message"));
        $backend->setVisible(array("id", "to_user_id",  "from_user_id", "date"));
        $backend->setSearchable(array("id", "to_user_id",  "from_user_id", "date"));
        
        $backend->addLabel("from_user_id", "From User");
        $backend->addLabel("to_user_id", "To User");
        
        $backend->setRelation("to_user_id", "User", "id")->showFields("name");
        $backend->setRelation("from_user_id", "User", "id")->showFields("name");
        
        return $backend;
        
    }
    
    public function cleanup()
    {
        $enddate=  strtotime("-2 days");
        $sql = "DELETE FROM Notification WHERE date<:enddate";
        
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':enddate', $enddate, \PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * @param $auth_cookie
     * @return mixed
     */
    public function getNotificationsByCookie($auth_cookie)
    {
        $sql = "SELECT Notification.*, "
                . "fromUser.* FROM Notification"
                . " INNER JOIN User ON "
                . "Notification.to_user_id=User.id AND "
                . "User.auth_cookie=:auth_cookie "
                . " LEFT JOIN User fromUser ON "
                . "Notification.from_user_id = fromUser.id ORDER BY date DESC";
        
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':auth_cookie', $auth_cookie, \PDO::PARAM_STR);

        $stmt->execute();

        $obj = $stmt->fetchALL(\PDO::FETCH_CLASS, "\stdClass");

        return $obj;
        
    
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getNotificationsByID($id)
    {
        
        $sql = "SELECT Notification.*, "
                . "fromUser.* FROM Notification"
                . " INNER JOIN User ON "
                . "Notification.to_user_id=User.id AND "
                . "User.id=:id "
                . " LEFT JOIN User fromUser on "
                . "Notification.from_user_id = fromUser.id ORDER BY date DESC";
        
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        $stmt->execute();

        $obj = $stmt->fetchALL(\PDO::FETCH_CLASS, "\stdClass");

        return $obj;
        
    
    }
}
