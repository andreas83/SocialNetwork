<?php

/**
 * User Model
 * 
 * @inject backend
 */
class User extends BaseModel
{

    
 
    public $id = "";
    public $name = "";
    public $mail = "";
    public $password = "";
    public $settings = "";
    public $api_key = "";
    public $auth_cookie = "";
    public $isAdmin = "";
    public $created = "";

    public function getPrimary()
    {
        return "id";
    }
    
    public function getBackendConfiguration(){
     $backend = new ConfigureBackend;
     $backend->setEditable(array("id", "name", "mail", "api_key", "isAdmin"));
     $backend->setVisible(array("name", "mail"));
     return $backend;
        
    }
    
    public function getUserbyAPIKey($secret){
        $sql = "select * from User where api_key=:api";
        
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':api', $secret, PDO::PARAM_STR);

        $stmt->execute();

        $obj = $stmt->fetchALL(PDO::FETCH_CLASS, 'User');

        return $obj;
        
    }
    
}
