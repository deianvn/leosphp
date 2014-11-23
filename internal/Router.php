<?php

namespace ls\internal;

class Router {
    
    private $ls;
    
    private $applicationAliases;
    
    private $actionAliases;
    
    private $routes;
    
    public function __construct($ls) {
        $this->ls = $ls;
        $this->applicationAliases = array();
        $this->actionAliases = array();
        $this->routes = array();
    }
    
    /**
     * 
     * @return \ls\internal\LS
     */
    public function getLs() {
        return $this->ls;
    }
    
    public function addApplicationAlias($application, $alias) {
        $this->applicationAliases[$application] = $alias;
    }
    
    public function addActionAlias($action, $alias) {
        $this->actionAliases[$action] = $alias;
    }
    
    public function addRoute($route, $target) {
        $this->routes[$route] = $target;
    }
    
    public function route($uri) {
        
    }
    
    /**
     * 
     * @return boolean
     */
    public function hasRoutesFile() {
        return file_exists($this->makeRoutesFilePath());
    }
    
    /**
     * 
     * @return boolean
     */
    public function loadRoutesFile() {
        if ($this->hasRoutesFile()) {
            require $this->makeRoutesFilePath();
        }
        
        return false;
    }
    
    /**
     * 
     * @return string
     */
    private function makeRoutesFilePath() {
        return $this->getLs()->getPath() . 'routes.php';
    }
    
}
