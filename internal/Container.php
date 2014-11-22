<?php

namespace ls\internal;

abstract class Container {
    
    private $name;
    
    private $ls;
    
    public abstract function getPath();
    
    public function __construct($name, $ls) {
        $this->name = $name;
        $this->ls = $ls;
    }
    
    public function getName() {
        return $this->name;
    }

    public function getLs() {
        return $this->ls;
    }
    
    public function isCreated() {
        return file_exists($this->getPath());
    }
    
    public function create() {
        if ($this->isBuilt() === false) {
            return mkdir($this->getPath());
        }
        
        return false;
    }
    
    public function delete() {
        if ($this->isCreated()) {
            rrmdir($this->getPath());
            return $this->isCreated();
        }
        
        return false;
    }
    
}
