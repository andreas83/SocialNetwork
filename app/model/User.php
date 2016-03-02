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
    public $isAdmin = "0";
    public $created = "";

    public function getPrimary()
    {
        return "id";
    }
    
    public function getBackendConfiguration(){
        $backend = new ConfigureBackend;
        $backend->setSearchable(array("id", "name", "mail", "api_key", "isAdmin"));
        $backend->setEditable(array("id", "name", "mail", "api_key", "isAdmin"));
        $backend->setVisible(array("name", "mail", "isAdmin"));
        $backend->addCheckbox("isAdmin", array("1"=>"Yes", "0" =>"No"));
        
        
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
    
    /*
     * Attention, the result is used by autocompleter,
     * do not select sensitive information here.
     */
    public function getUserbyName($name){
        $sql = "select name, settings from User where name like :name";
        
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':name', "%".$name."%", PDO::PARAM_STR);

        $stmt->execute();

        $obj = $stmt->fetchALL(PDO::FETCH_CLASS, 'User');

        return $obj;
        
    }
    
}
