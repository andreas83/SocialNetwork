<?php

class Comment extends BaseApp
{

    public $id = "";
    public $content_id = "";
    public $user_id = "";
    public $comment = "";
    public $date = "";


    public function getPrimary()
    {
        return "id";
    }
    
    
    function getComment($id)
    {
    
        $sql = "SELECT comment,  User.name, settings FROM Comment, User WHERE Comment.user_id=User.id and Comment.content_id=:id order by Comment.id desc";

        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $obj = $stmt->fetchALL(PDO::FETCH_CLASS, 'Comment');

        return $obj;
    
    
    }
}
?>