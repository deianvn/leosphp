<?php

namespace ls\core;

abstract class LSBaseAction extends LSCommon {

    private $formParams;
    private $cachelets;
    private $pageCacheEnabled;
    private $cacheletCacheEnabled;
    
    public function __construct() {
        $this->formParams = array();
        $this->cachelets = array();
        $this->pageCacheEnabled = false;
        $this->cacheletCacheEnabled = false;
        $this->loadAutoLoad();
    }
    
    protected function setPageCacheEnabled($enabled) {
        $this->pageCacheEnabled = $enabled;
    }
    
    public function isPageCacheEnabled() {
        return $this->pageCacheEnabled === true;
    }
    
    protected function setCacheletCacheEnabled($enabled) {
        $this->cacheletCacheEnabled = $enabled;
    }
    
    public function isCacheletCacheEnabled() {
        return $this->cacheletCacheEnabled === true;
    }

    protected function registerFormParam($type, $name, $scope) {
        $this->formParams[] = LSFormParam::create($type, $name, $scope);
    }
    
    public function isFormParamRegistered($name, $scope) {
        foreach ($this->formParams as $param) {
            if ($param->name === $name && ($param->scope === 'all' || $param->scope === $scope)) {
                return true;
            }
        }
        
        return false;
    }
    
    public function getFormParamType($name) {
        foreach ($this->formParams as $param) {
            if ($param->name === $name) {
                return $param->type;
            }
        }
        
        return false;
    }
    
    protected function registerCachelet($cachelet) {
        if ($cachelet instanceof LSCachelet) {
            $this->cachelets[] = $cachelet;
        } else {
            LSLogger::error('LSCachelet paramter required');
            exit(1);
        }
    }
    
    protected function scriptlet($name) {
        $path = getScriptletPath($name);
        
        if ($path !== null) {
            require $path;
            clearContext();
        }
    }
    
    protected function page($name) {
        new LSPage($name);
        exit(0);
    }
    
    private function loadAutoLoad() {
        require wget('Application')->getPath() . 'autoload.php';
    }

}
