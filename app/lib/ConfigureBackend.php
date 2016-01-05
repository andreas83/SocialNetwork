<?php


class ConfigureBackend {
    
    var $searchable;
    var $editable;
    var $visible;
    
    public function setSearchable($properties){
        $this->searchable=$properties;
    }
    
    public function setEditable($properties){
        $this->editable=$properties;
    }
    
    public function setVisible($properties){
        $this->visible=$properties;
    }
    
    public function setCheckbox($properties){
        $this->checkbox=$properties;
    }
    
    
}
