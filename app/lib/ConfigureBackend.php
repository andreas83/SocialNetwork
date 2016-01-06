<?php


class ConfigureBackend {
    
    var $searchable;
    var $editable;
    var $visible;
    var $properties;
    protected $currentField;
    
    public function setSearchable($properties){
        $this->searchable=$properties;
        return $this;
    }
    
    public function setEditable($properties){
        $this->editable=$properties;
        return $this;
    }
    
    public function setVisible($properties){
        $this->visible=$properties;
        return $this;
    }
    
    public function setRelation($propertie, $relatedObject, $relatedField)
    {
        $this->currentField=$propertie;
        $this->properties[$propertie]['relation']=$relatedObject;
        $this->properties[$propertie]['field']=$relatedField;
        return $this;
    }
    public function showFields($fields)
    {
        $this->properties[$this->currentField]['show']=$fields;
    }
    
    
    
    public function addLabel($propertie, $label)
    {
        $this->properties[$propertie]['label']=$label;
        return $this;
    }
    
    public function addCheckbox($propertie, $values){
        $this->properties[$propertie]['type']="checkbox";
        $this->properties[$propertie]['values']=$values;
        return $this;
    }
    public function addTextarea($propertie){
        
        $this->properties[$propertie]['type']="textarea";
        return $this;
    }
    
    
}
