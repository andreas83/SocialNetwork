<?php

class Groups extends BaseModel
{

    public $group_id = "";
    public $name = "";
    public $description = "";
    public $image = "";
    public $visibility = "PUBLIC";
    public $created = "";
    public $modified = "";
    


    

    public function getPrimary()
    {
        return "id";
    }
    
    function save(){
        
        if($this->id=="")
            $this->created=date("Y-m-d H:i:s");
        
        $this->modified=date("Y-m-d H:i:s");
        parent::save();
    }
    
    public function getBackendConfiguration(){
        $backend = new ConfigureBackend;
        $backend->setEditable(array("id", "name","description" ));
        $backend->setVisible(array("id", "name", "description"));
        $backend->setSearchable(array("id", "name", "description"));
        return $backend;
        
    }
    
}
?>
