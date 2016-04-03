<?php
namespace SocialNetwork\app\model;

use SocialNetwork\app\lib\BaseModel;

/**
 * Class Score
 * @package SocialNetwork\app\model
 */
class Score extends BaseModel
{

    public $id = "";
    public $user_id = "";
    public $content_id = "";
    public $type = "";


    /**
     * @return string
     */
    public function getSource()
    {
        return 'Score';
    }

    /**
     * @return string
     */
    public function getPrimary()
    {
        return "id";
    }

    public function getBackendConfiguration()
    {
        return [];
    }


    /**
     * @param int $id
     * @return mixed
     */
    public function getScore($id)
    {
        $sql = "SELECT COUNT(id) AS cnt, Score.* FROM Score WHERE content_id=:id GROUP BY type";
        
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $obj = $stmt->fetchALL(PDO::FETCH_CLASS, get_class($this));

        return $obj;
        
    }
}