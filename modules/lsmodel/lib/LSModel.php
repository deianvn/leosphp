<?php

namespace ls\model;

abstract class LSModel {
    
    public abstract function setFromArray($data);
    
    public abstract function setFromAssoc($data);
    
    public abstract function toArray();
    
    public abstract function toAssoc();
    
    public abstract function manager();
    
    public abstract function save();
    
    protected abstract function attribute($name);
    
    public function attributeIsEmpty($name) {
        return $this->attribute($name)->isEmpty();
    }
    
    public function attributeEmpty($name) {
        $this->attribute($name)->setEmpty(true);
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
