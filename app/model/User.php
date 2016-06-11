<?php
namespace SocialNetwork\app\model;
use SocialNetwork\app\lib\BaseModel;
use SocialNetwork\app\lib\ConfigureBackend;

/**
 * Class User
 * @package SocialNetwork\app\model
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


    /**
     * @return string
     */
    public function getSource()
    {
        return 'User';
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
        $backend->setSearchable(array("id", "name", "mail", "api_key", "isAdmin"));
        $backend->setEditable(array("id", "name", "mail", "api_key", "isAdmin"));
        $backend->setVisible(array("name", "mail", "isAdmin"));
        $backend->addCheckbox("isAdmin", array("1"=>"Yes", "0" =>"No"));

        return $backend;
    }

    /**
     * @param $secret
     * @return mixed
     */
    public function getUserbyAPIKey($secret)
    {
        $sql = "SELECT * FROM User WHERE api_key=:api";
        
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':api', $secret, \PDO::PARAM_STR);

        $stmt->execute();

        $obj = $stmt->fetchALL(\PDO::FETCH_CLASS, get_class($this));

        return $obj;
    }
    
    /*
     * Attention, the result is used by autocompleter,
     * do not select sensitive information here.
     *
     * @param string $name
     * @return mixed
     */
    public function getUserbyName($name)
    {
        $sql = "SELECT name, settings FROM User WHERE name LIKE :name";
        
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':name', "%".$name."%", \PDO::PARAM_STR);

        $stmt->execute();

        $obj = $stmt->fetchALL(\PDO::FETCH_CLASS, get_class($this));

        return $obj;
        
    }
    
    function save() {
        $this->modified=date("Y-m-d H:i:s");
        return parent::save();
    }

    /**
     * @return mixed
     */
    function getStats()
    {
        $sql="SELECT MONTH(created) AS Month, YEAR(created) AS Year, COUNT(*) AS cnt FROM User GROUP BY month(created), YEAR(created) ORDER BY month(created), YEAR(created)";
        
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();

        $obj = $stmt->fetchALL(\PDO::FETCH_CLASS, '\stdClass');

        return $obj;
    }

    /**
     * @param string $month
     * @param string $year
     * @return mixed
     */
    function getActiveUsers($month, $year)
    {
        $sql = "SELECT name FROM User WHERE YEAR(modified)=:year AND MONTH(modified)=:month";
        
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':year', $year, \PDO::PARAM_STR);
        $stmt->bindValue(':month', $month, \PDO::PARAM_STR);

        $stmt->execute();

        $obj = $stmt->fetchALL(\PDO::FETCH_CLASS, get_class($this));

        return $obj;
                
    }
    
}
