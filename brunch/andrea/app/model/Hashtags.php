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
        
        $sql = "select * from hashtags where hashtag like :term order by pop";
        
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':term', "%".$term."%", PDO::PARAM_STR);

        $stmt->execute();

        $obj = $stmt->fetchALL(PDO::FETCH_CLASS, 'stdClass');

        return $obj;
        
    
    }
}
?>
