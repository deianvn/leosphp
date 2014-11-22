<?php

namespace ls\model;

class LSAttribute {    
    private $value = null;
    private $type = null;
    private $changed = false;
    private $empty = true;
    
    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        
        if ($value === null && $this->empty === true) {
            $this->changed = true;
            $this->empty = false;
            
            return;
        }
        
        if ($this->value !== $value) {
            $this->changed = true;
            $this->value = $value;
            $this->empty = false;
        }
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function setType($type) {
        $this->type = $type;
    }
    
    public function setChanged($changed) {
        $this->changed = $changed;
    }
    
    public function isChanged() {
        return $this->changed;
    }
    
    public function isEmpty() {
        return $this->empty;
    }
    
    public function setEmpty($empty) {
        if ($empty === true) {
            $this->empty = true;
            $this->value = null;
        } else {
            $this->empty = false;
        }
    }

}
