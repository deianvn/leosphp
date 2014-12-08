<?php

namespace ls\orm;

abstract class Model {
    
    private $attributes;
    
    private $attributeDescriptors;
    
    function __construct() {
        $this->attributes = array();
        $this->attributeDescriptors = array();
        $this->init();
    }
    
    public static function manager() {
        
    }
    
    protected abstract function init();
    
    public function registerAttribute($name) {
        $attribureDesciptor = new AttributeDescriptor($name);
        $this->attributeDescriptors[] = $attribureDesciptor;
        return $attribureDesciptor;
    }
    
    public function descriptors() {
        return $this->attributeDescriptors;
    }
    
    public function setFromArray($data) {
        
    }
    
    public function setFromAssoc($data) {
        
    }
    
    public function toArray() {
        
    }
    
    public function toAssoc() {
        
    }
    
    public function save() {
        
    }
    
    public function attributeGet($name) {
        
    }
    
    public function attributeSet($name, $value) {
        
    }
    
    public function attributeIsEmpty($name) {
        return $this->attributeGet($name)->isEmpty();
    }
    
    public function attributeEmpty($name) {
        $this->attributeGet($name)->setEmpty(true);
    }
    
    public function attributeGetType($name) {
        return $this->attribute($name)->getType();
    }
    
    public function attributeIsChanged($name) {
        return $this->attribute($name)->isChanged();
    }
    
    public function attributeClearChangeState($name) {
        $this->attribute($name)->setChanged(false);
    }
    
}
