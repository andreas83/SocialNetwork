<?php

class Content extends BaseApp
{

    public $id = "";
    public $user_id = "";
    public $data = "";
    public $media = "";
    public $date = "";

    public function getPrimary()
    {
        return "id";
    }

    public function getNext($id = false, $show = 10, $hash = false, $user =false)
    {
        
        $esql="";

        if ($hash) {
            $esql.= " (data LIKE :term or media LIKE :term) AND";
        } 

        if($user){
            $esql.= " User.id=(select id from User where name = :username) AND";
        }
        $sql = "SELECT *, Content.id AS id, User.id as user_id FROM Content, User "
                . "WHERE  $esql "
                . "Content.user_id=User.id AND "
                . "Content.id < $id "
                . "ORDER BY Content.id desc limit $show";
        
        
        $stmt = $this->dbh->prepare($sql);

        if($hash)
            $stmt->bindValue(':term', "%" . $hash . "%", PDO::PARAM_STR);
        if($user)
            $stmt->bindValue(':username', str_replace(".", " ", $user), PDO::PARAM_STR);

        $stmt->execute();

        $obj = $stmt->fetchALL(PDO::FETCH_CLASS, 'Content');

        return $obj;
    }

}
