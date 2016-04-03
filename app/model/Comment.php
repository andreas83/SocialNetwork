<?php
namespace SocialNetwork\app\model;


use SocialNetwork\app\lib\BaseModel;
use SocialNetwork\app\lib\ConfigureBackend;

class Comment extends BaseModel
{

    public $id = "";
    public $content_id = "";
    public $user_id = "";
    public $comment = "";
    public $date = "";


    /**
     * @return string
     */
    public function getSource()
    {
        return 'Comment';
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
        $backend->setEditable(array("id", "content_id", "user_id", "comment"));
        $backend->setVisible(array("id", "content_id", "user_id", "comment"));
        $backend->setSearchable(array("user_id", "content_id", "comment"));
        $backend->addTextarea("comment");
        $backend->addLabel("user_id", "Username");
        $backend->setRelation("user_id", "User", "id")->showFields("name");
        
        
        return $backend;
        
    }

    /**
     * @param int $id
     * @return mixed
     */
    function getComment($id)
    {
    
        $sql = "SELECT comment,  User.name, settings FROM Comment, User WHERE Comment.user_id=User.id and Comment.content_id=:id order by Comment.id desc";

        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        $stmt->execute();

        $obj = $stmt->fetchALL(\PDO::FETCH_CLASS, get_class($this));

        return $obj;
    }
}
