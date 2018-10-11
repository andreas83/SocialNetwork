<?php
namespace SocialNetwork\app\lib;

class ConfigureBackend
{
    public $searchable;
    public $editable;
    public $visible;
    public $properties;
    protected $currentField;

    /**
     * @param $properties
     * @return $this
     */
    public function setSearchable($properties)
    {
        $this->searchable=$properties;
        return $this;
    }

    /**
     * @param $properties
     * @return $this
     */
    public function setEditable($properties)
    {
        $this->editable=$properties;
        return $this;
    }

    /**
     * @param array $properties
     * @return $this
     */
    public function setVisible($properties)
    {
        $this->visible=$properties;
        return $this;
    }

    /**
     * @param string $property
     * @param $relatedObject
     * @param $relatedField
     * @return $this
     */
    public function setRelation($property, $relatedObject, $relatedField)
    {
        $this->currentField=$property;
        $this->properties[$property]['relation']=$relatedObject;
        $this->properties[$property]['field']=$relatedField;
        return $this;
    }

    /**
     * @param array $fields
     */
    public function showFields($fields)
    {
        $this->properties[$this->currentField]['show']=$fields;
    }

    /**
     * @param string $property
     * @param string $label
     * @return $this
     */
    public function addLabel($property, $label)
    {
        $this->properties[$property]['label']=$label;
        return $this;
    }

    /**
     * @param string $property
     * @param mixed $values
     * @return $this
     */
    public function addCheckbox($property, $values)
    {
        $this->properties[$property]['type']="checkbox";
        $this->properties[$property]['values']=$values;
        return $this;
    }

    /**
     * @param string $property
     * @return $this
     */
    public function addTextarea($property)
    {
        $this->properties[$property]['type']="textarea";
        return $this;
    }
}
