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
    public $modified= "";

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
    
    function save(){
        $this->modified=date("Y-m-d H:i:s");
        parent::save();
    }
    
    function getStats(){
        
        $sql="select Month(created) as Month, YEAR(created) as Year, count(*) as cnt from User GROUP BY Month(created), YEAR(created) order by created";
        
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();

        $obj = $stmt->fetchALL(PDO::FETCH_CLASS, 'StdClass');

        return $obj;
    }
    
    function getActiveUsers($month, $year)
    {
        $sql = "select name from User where year(modified)=:year and month(modified)=:month";
        
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':year', $year, PDO::PARAM_STR);
        $stmt->bindValue(':month', $month, PDO::PARAM_STR);

        $stmt->execute();

        $obj = $stmt->fetchALL(PDO::FETCH_CLASS, 'User');

        return $obj;
                
    }
    
}
