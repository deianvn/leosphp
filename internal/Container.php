<?php

namespace ls\internal;

abstract class Container {
    
    private $name;
    
    private $application;
    
    /**
     * 
     */
    public abstract function getPath();
    
    /**
     * 
     * @param string $name
     * @param \ls\internal\Application $application
     */
    public function __construct($name, $application) {
        $this->name = $name;
        $this->application = $application;
    }
    
    /**
     * 
     * @return string
     */
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
    
    /**
     * 
     * @return boolean
     */
    public function isCreated() {
        return file_exists($this->getPath());
    }
    
    /**
     * 
     * @return boolean
     */
    public function create() {
        if ($this->isCreated() === false) {
            return mkdir($this->getPath());
        }
        
        return false;
    }
    
    /**
     * 
     * @return boolean
     */
    public function delete() {
        if ($this->isCreated()) {
            rrmdir($this->getPath());
            return !$this->isCreated();
        }
        
        return false;
    }
    
}
