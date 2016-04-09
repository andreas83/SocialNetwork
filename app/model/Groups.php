<?php
namespace SocialNetwork\app\model;

use SocialNetwork\app\lib\BaseModel;
use SocialNetwork\app\lib\ConfigureBackend;

class Groups extends BaseModel
{

    public $group_id = "";
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
        
        $sql="select * from Groups, User_Group "
                . "where visibility ='PUBLIC' or "
                . "User_Group.group_id=Groups.group_id and user_id=:user_id";
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        $stmt->execute();

        $obj = $stmt->fetchALL(PDO::FETCH_CLASS, 'Groups');

        return $obj;        
        
        
    }
    
    public function addToGroup(int $group_id, int $user_id, $isAdmin =false){
        
        $sql="insert into User_Group values (:group_id, :user_id, :admin, now(), now() )";
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':admin', ($isAdmin ? 1: 0), PDO::PARAM_INT);
        
        $stmt->execute();

        
    }
    
    
    
}
?>
