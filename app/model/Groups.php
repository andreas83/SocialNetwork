<?php
namespace SocialNetwork\app\model;

use SocialNetwork\app\lib\BaseModel;
use SocialNetwork\app\lib\ConfigureBackend;

/**
 * Class Groups
 * @package SocialNetwork\app\model
 */
class Groups extends BaseModel
{

    public $group_id = "";
    public $parent_id = "";
    public $name = "";
    public $description = "";
    public $image = "";
    public $visibility = "PUBLIC";
    public $created = "";
    public $modified = "";
    
    /**
     * @return ConfigureBackend
     */
    public function getBackendConfiguration(){
     $backend = new ConfigureBackend;
     $backend->setEditable(array("group_id", "name", "visibility", "modified"));
     $backend->setVisible(array("group_id", "name", "visibility", "modified"));
    
     

     return $backend;
    }
    
    /**
     * @return string
     */
    public function getSource()
    {
        return 'Groups';
    }
    
    public function getPrimary()
    {
        return "group_id";

    }
    
    function save(){
        
        if($this->group_id=="")
            $this->created=date("Y-m-d H:i:s");
        
        parent::save();
    }
    
    
    public function getGroups($user_id){
        
        $sql="select Groups.* , "
                . "(select count(user_id) from User_Group where User_Group.group_id=Groups.group_id) as cnt, "
                . "(select count(user_id) from Content where Content.group_id=Groups.group_id) as content_cnt "
                . "from Groups LEFT JOIN User_Group on User_Group.group_id=Groups.group_id and user_id=:user_id "
                . "where visibility ='PUBLIC'  "
                . " ";
        
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':user_id', $user_id, \PDO::PARAM_INT);

        $stmt->execute();

        $obj = $stmt->fetchALL(\PDO::FETCH_CLASS, 'SocialNetwork\app\model\Groups');

        return $obj;        
        
        
    }
    
    public function addToGroup( $group_id,  $user_id, $isAdmin =false){
        
        $sql="insert into User_Group values (:group_id, :user_id, :admin, now(), now() )";
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':group_id', $group_id, \PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, \PDO::PARAM_INT);
        $stmt->bindValue(':admin', ($isAdmin ? 1: 0), \PDO::PARAM_INT);
        
        $stmt->execute();
        
        
    }
    
    
    
}