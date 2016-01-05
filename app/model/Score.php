<?php

class Score extends BaseModel
{

    public $id = "";
    public $user_id = "";
    public $content_id = "";
    public $type = "";
    

    public function getPrimary()
    {
        return "id";
    }
    
    public function getScore($id){
        $sql = "select count(id) as cnt, Score.* from Score where content_id=:id group by type";
        
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $obj = $stmt->fetchALL(PDO::FETCH_CLASS, 'Score');

        return $obj;
        
    }
}
?>
