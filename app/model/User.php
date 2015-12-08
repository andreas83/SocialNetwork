<?php


class User extends BaseApp
{

    public $id = "";
    public $name = "";
    public $mail = "";
    public $password = "";
    public $settings = "";
    public $api_key = "";
    public $auth_cookie = "";
    public $created = "";

    public function getPrimary()
    {
        return "id";
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
