<?php
namespace SocialNetwork\app\model;

use SocialNetwork\app\lib\BaseModel;
use SocialNetwork\app\lib\ConfigureBackend;

/**
 * Class Content
 * @package SocialNetwork\app\model
 */
class Content extends BaseModel
{
    const maxid= 1000000000;

    public $id = "";
    public $user_id = "";
    public $data = "";
    public $media = "";
    public $date = "";

    /**
     * @return string
     */
    public function getSource()
    {
        return 'Content';
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
    public function getBackendConfiguration(){
     $backend = new ConfigureBackend;
     $backend->setEditable(array("id", "user_id", "data", "media", "date"));
     $backend->setVisible(array("id", "user_id", "data",  "date"));
     
     $backend->setRelation("user_id", "User", "id")->showFields("name");
     $backend->addLabel("user_id", "Username");
     
     $backend->setSearchable(array("id", "data", "media"));
     $backend->addTextarea("data");
     

     return $backend;
    }

    /**
     * get new Data
     * 
     * @todo profiling, there is some room for optimisation (i.e. nsfw filter)
     * 
     * @param int $id
     * @param int $show
     * @param string $hash
     * @param int $user
     * @param string $type img, video, www
     * @param string $order 
     * @return type
     */
    public function getNext($id = false, $show = 10, $hash = false, $user =false, $type=false, $order=false, $show_nsfw="false")
    {
        $id=(isset($id) && $id ? $id : Content::maxid);
        
        $esql="";
        
        if ($hash) {
            $esql.= " (data LIKE :term OR media LIKE :term) AND";
        } 

        if($user){
            $esql.= " User.id=(SELECT id FROM User WHERE name = :username) AND";
        }
        if($type){
            $esql.= " (media LIKE :type) AND";
        }
        
        if($show_nsfw=="false")
        {
            $esql.="  data NOT LIKE '%nsfw%' AND";
        }
              
        $orderby="ORDER BY Content.id DESC";
        
        if($order)
        {
            $orderby=$order;
        }
        
        
        $sql = "SELECT *, Content.id AS id, Content.date, User.id AS user_id, "
                . " (select count(*) from Comment where content_id=Content.id) as comment_cnt "
                . "FROM Content "
                . "INNER JOIN User ON Content.user_id=User.id "
                . "WHERE  $esql "
                . "Content.id < $id "
                . "$orderby LIMIT $show";
  
        
        $stmt = $this->dbh->prepare($sql);

        if($hash)
            $stmt->bindValue(':term', "%" . $hash . "%", \PDO::PARAM_STR);
        if($user)
            $stmt->bindValue(':username', str_replace(".", " ", $user), \PDO::PARAM_STR);
        if($type){
            $stmt->bindValue(':type', '%type":"'.$type.'"%', \PDO::PARAM_STR);
        }
        
        $stmt->execute();

        $obj = $stmt->fetchALL(\PDO::FETCH_CLASS, get_class($this));

        return $obj;
    }

    /**
     * @return mixed
     */
    function getStats(){
        
        $sql="SELECT MONTH(FROM_UNIXTIME(date)) AS Month, YEAR(FROM_UNIXTIME(date)) AS Year, COUNT(*) AS cnt FROM Content GROUP BY MONTH(FROM_UNIXTIME(date)), YEAR(FROM_UNIXTIME(date)) ORDER BY MONTH(FROM_UNIXTIME(date)), YEAR(FROM_UNIXTIME(date))";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();

        $obj = $stmt->fetchALL(\PDO::FETCH_CLASS, '\stdClass');

        return $obj;
    }

}
