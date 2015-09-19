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

    public function getNext($id = false, $show = 10, $term = false)
    {

        if ($term) {
            $esql = " data LIKE :term AND";
        } else {
            $esql = "";
        }

        $sql = "SELECT *, Content.id AS id, User.id as user_id FROM Content, User WHERE  $esql Content.user_id=User.id AND Content.id < $id ORDER BY Content.id desc limit $show";

        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':term', "%" . $term . "%", PDO::PARAM_STR);

        $stmt->execute();

        $obj = $stmt->fetchALL(PDO::FETCH_CLASS, 'Content');

        return $obj;
    }

}
