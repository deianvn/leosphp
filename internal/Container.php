<?php

namespace ls\internal;

abstract class Container {
    
    private $name;
    
    private $application;
    
    public abstract function getPath();
    
    public function __construct($name, $application) {
        $this->name = $name;
        $this->application = $application;
    }
    
    public function getName() {
        return $this->name;
    }
    
    /**
     * 
     * @return \ls\internal\Application
     */
    public function getApplication() {
        return $this->application;
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
