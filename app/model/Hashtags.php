<?php

class Hashtags extends BaseApp
{

    public $id = "";
    public $hashtag = "";
    public $pop = "";
    
    

    public function getPrimary()
    {
        return "id";
    }
    
    public function findHashtags($term) {
        
        $sql = "select * from Hashtags where hashtag like :term order by pop desc";
        
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':term', "%".$term."%", PDO::PARAM_STR);

        $stmt->execute();

        $obj = $stmt->fetchALL(PDO::FETCH_CLASS, 'Hashtags');

        return $obj;
        
    
    }
}
?>
