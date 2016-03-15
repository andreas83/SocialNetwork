<?php

class Content extends BaseModel
{

    public $id = "";
    public $user_id = "";
    public $data = "";
    public $media = "";
    public $date = "";

    const maxid= 1000000000;
    
    public function getPrimary()
    {
        return "id";
    }
    
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
            $esql.= " (data LIKE :term or media LIKE :term) AND";
        } 

        if($user){
            $esql.= " User.id=(select id from User where name = :username) AND";
        }
        if($type){
            $esql.= " (media LIKE :type) AND";
        }
        
        if($show_nsfw=="false")
        {
            $esql.="  data not like '%nsfw%' AND";
        }
              
        $orderby="order by Content.id desc"; 
        
        if($order)
        {
            $orderby=$order;
        }
        
        
        $sql = "SELECT *, $score Content.id AS id, Content.date, User.id as user_id "
                . "FROM Content "
                . "INNER JOIN User on Content.user_id=User.id "               
                . "WHERE  $esql "
                . "Content.id < $id "
                . "$orderby limit $show";
  
        
        $stmt = $this->dbh->prepare($sql);

        if($hash)
            $stmt->bindValue(':term', "%" . $hash . "%", PDO::PARAM_STR);
        if($user)
            $stmt->bindValue(':username', str_replace(".", " ", $user), PDO::PARAM_STR);
        if($type){
            $stmt->bindValue(':type', '%type":"'.$type.'"%', PDO::PARAM_STR);
        }
        
        $stmt->execute();

        $obj = $stmt->fetchALL(PDO::FETCH_CLASS, 'Content');

        return $obj;
    }

}
