<?php
namespace SocialNetwork\app\model;

use SocialNetwork\app\lib\BaseModel;
use SocialNetwork\app\lib\ConfigureBackend;

/**
 * Class Hashtags
 * @package SocialNetwork\app\model
 */
class Hashtags extends BaseModel
{

    public $id = "";
    public $hashtag = "";
    public $pop = "";
    public $modified = "";


    /**
     * @return string
     */
    public function getSource()
    {
        return 'Hashtags';
    }

    /**
     * @return string
     */
    public function getPrimary()
    {
        return "id";
    }


    function save() {
        $this->modified=date("Y-m-d H:i:s");
        parent::save();
    }

    /**
     * @return ConfigureBackend
     */
    public function getBackendConfiguration(){
        $backend = new ConfigureBackend;
        $backend->setEditable(array("id", "hashtag", "pop"));
        $backend->setVisible(array("id", "hashtag", "pop"));
        $backend->setSearchable(array("id", "hashtag", "pop"));
        return $backend;
        
    }

    /**
     * @param $term
     * @return mixed
     */
    public function findHashtags($term)
    {
        $sql = "SELECT * FROM Hashtags WHERE hashtag LIKE :term ORDER BY pop DESC";
        
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':term', "%".$term."%", \PDO::PARAM_STR);

        $stmt->execute();

        $obj = $stmt->fetchALL(\PDO::FETCH_CLASS, get_class($this));

        return $obj;
    }

    /**
     * @param int $limit
     * @return mixed
     */
    function getPopularHashtags($limit =5)
    {
        $sql = "SELECT * FROM Hashtags ORDER BY pop DESC LIMIT ". (int) $limit;
        
        $stmt = $this->dbh->prepare($sql);

        

        $stmt->execute();

        $obj = $stmt->fetchALL(\PDO::FETCH_CLASS, get_class($this));

        return $obj;        
    }

    /**
     * @param int $limit
     * @return mixed
     */
    function getTrendingHashtags($limit =5)
    {
        $sql = "SELECT * FROM Hashtags  WHERE WEEK(modified) = WEEK(now()) ORDER BY pop DESC limit ". (int) $limit;
        
        $stmt = $this->dbh->prepare($sql);

        

        $stmt->execute();

        $obj = $stmt->fetchALL(\PDO::FETCH_CLASS, get_class($this));

        return $obj;        
    }

    /**
     * @param int $limit
     * @return mixed
     */
    function getRandomHashtags($limit =5)
    {
        $sql = "SELECT * FROM Hashtags ORDER BY RAND() LIMIT ". (int) $limit;
        
        $stmt = $this->dbh->prepare($sql);

        

        $stmt->execute();

        $obj = $stmt->fetchALL(\PDO::FETCH_CLASS, get_class($this));

        return $obj;        
    }
}

