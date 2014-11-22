<?php

namespace ls\core;

class LSApplication extends LSCommon {
    
    private $name;
    private $path;
    private $modules;
    private $enabled;
    private $cacheResourcesEnabled;
    private $defaultAction;
    private $defaultActionParams;
    private $defaultActionRedirectEnabled;
    private $localizationEnabled;
    private $defaultLocale;
    
    public function __construct($name) {
        wput('Application', $this);
        $this->name = $name;
        $this->path = INCLUDE_DIR . 'apps' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR;
        $this->enabled = false;
        $this->modules = array();
        $this->defaultActionParams = array();
        $this->loadConfig();
    }
    
    public function getPath() {
        return $this->path;
    }
    
    public function addModule($name, $configuration = null) {
        $this->modules[] = $name;
        
        if (is_array($configuration)) {
            foreach ($configuration as $key => $value) {
                wput('Module:' . $name . ':Config:' . $key, $value);
            }
        }
    }
    
    public function getModules() {
        return $this->modules;
    }
    
    protected function setEnabled($enabled) {
        $this->enabled = $enabled;
    }
    
    public function isEnabled() {
        return $this->enabled;
    }
    
    protected function setCacheResourcesEnabled($cacheResourcesEnabled) {
        $this->cacheResourcesEnabled = $cacheResourcesEnabled;
    }
    
    public function isCacheResourcesEnabled() {
        return $this->cacheResourcesEnabled;
    }
    
    protected function setDefaultAction($action) {
        $this->defaultAction = $action;
    }
    
    public function getDefaultAction() {
        return $this->defaultAction;
    }
    
    protected function addDefaultActionParam($param) {
        $this->defaultActionParams[] = $param;
    }
    
    public function getDefaultActionParams() {
        return $this->defaultActionParams;
    }
    
    protected function setDefaultActionRedirectEnabled($defaultActionRedirectEnabled) {
        $this->defaultActionRedirectEnabled = $defaultActionRedirectEnabled;
    }
    
    public function isDefaultActionRedirectEnabled() {
        return $this->defaultActionRedirectEnabled;
    }
    
    protected function setLocalizationEnabled($localizationEnabled) {
        $this->localizationEnabled = $localizationEnabled;
    }
    
    public function isLocalizationEnabled() {
        return $this->localizationEnabled;
    }
    
    protected function setDefaultLocale($defaultLocale) {
        $this->defaultLocale = $defaultLocale;
    }
    
    public function getDefaultLocale() {
        return $this->defaultLocale;
    }
    
    private function loadConfig() {
        require $this->path . 'config.php';
    }
    
}
