<?php

class Hashtags extends BaseModel
{

    public $id = "";
    public $hashtag = "";
    public $pop = "";
    
    

    public function getPrimary()
    {
        return "id";
    }
    
    public function getBackendConfiguration(){
        $backend = new ConfigureBackend;
        $backend->setEditable(array("id", "hashtag", "pop"));
        $backend->setVisible(array("id", "hashtag", "pop"));
        $backend->setSearchable(array("id", "hashtag", "pop"));
        return $backend;
        
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
