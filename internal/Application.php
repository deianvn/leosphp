<?php

namespace ls\internal;

class Application extends ResourceContainer {
    
    /**
     *
     * @var \ls\internal\LS 
     */
    private $ls;
    
    private $enabled = false;
    
    private $modules = array();
    
    private $defaultActionName = null;
    
    private $defaultActionParameters = array();
    
    private $localizationEnabled = false;
    
    private $defaultLocale = null;
    
    private $resourceCachingEnabled = false;
    
    /**
     * 
     * @param string $name
     * @param \ls\internal\LS $ls
     */
    public function __construct($name, $ls) {
        parent::__construct($name, $this);
        $this->ls = $ls;
    }
    
    /**
     * 
     * @return \ls\internal\LS
     */
    public function getLs() {
        return $this->ls;
    }
    
    /**
     * 
     * @return string
     */
    public function getPath() {
        return BASE_DIR . 'apps/' . $this->getName() . '/';
    }
    
    /**
     * 
     * @param stirng $name
     * @param stirng $type
     * @param string $extension
     * @return \ls\internal\ResourceInfo|boolean
     */
    public function locateResource($name, $type, $extension = '.php') {
        if ($this->hasResource($name, $type, $extension)) {
            return new ResourceInfo($name, $type, $this, $extension);
        } else {
            foreach ($this->getModules() as $module) {
                if ($module->hasResource($name, $type, $extension) === true) {
                    return new ResourceInfo($name, $type, $module, $extension);
                }
            }
        }
        
        return false;
    }
    
    /**
     * 
     * @return boolean
     */
    public function getEnabled() {
        return $this->enabled;
    }

    /**
     * 
     * @param boolean $enabled
     */
    public function setEnabled($enabled) {
        $this->enabled = $enabled;
    }
    
    /**
     * 
     * @return \ls\internal\Module[]
     */
    public function getModules() {
        return $this->modules;
    }
    
    /**
     * 
     * @param string $name
     */
    public function addModule($name) {
        $this->modules[] = new Module($name, $this);
    }
    
    /**
     * 
     * @param string $name
     */
    public function removeModule($name) {
        $i = 0;
        foreach ($this->getModules() as $module) {
            if ($module->getName() === $name) {
                unset($this->modules[$i]);
                $this->modules = array_values($this->modules);
                
                return true;
            }
            
            $i++;
        }
        
        return false;
    }
    
    
    public function getDefaultActionName() {
        return $this->defaultActionName;
    }
    
    public function setDefaultActionName($defaultActionName) {
        $this->defaultActionName = $defaultActionName;
    }
    
    public function setDefaultActionParameters($defaultActionParameters) {
        $this->defaultActionParameters = $defaultActionParameters;
    }
    
    public function getDefaultActionParameters() {
        return $this->defaultActionParameters;
    }
    
    public function isLocalizationEnabled() {
        return $this->localizationEnabled;
    }

    public function setLocalizationEnabled($localizationEnabled) {
        $this->localizationEnabled = $localizationEnabled;
    }

    public function getDefaultLocale() {
        return $this->defaultLocale;
    }

    public function setDefaultLocale($defaultLocale) {
        $this->defaultLocale = $defaultLocale;
    }

    public function isResourceCachingEnabled() {
        return $this->resourceCachingEnabled;
    }

    public function setResourceCachingEnabled($resourceCachingEnabled) {
        $this->resourceCachingEnabled = $resourceCachingEnabled;
    }
                
    public function hasConfigurationFile() {
        return file_exists($this->makeConfigurationFilePath());
    }
    
    public function createConfigurationFile() {
        if ($this->isCreated() && $this->hasConfigurationFile() === false) {
            return file_put_contents($this->makeConfigurationFilePath(), $this->loadResourceTemplate('config', '.php'));
        }
        
        return false;
    }

    public function loadConfigurationFile() {
        if ($this->hasConfigurationFile()) {
            require $this->makeConfigurationFilePath();
        }
    }
    
    public function deleteConfigurationFile() {
        if ($this->hasConfigurationFile()) {
            return unlink($this->makeConfigurationFilePath());
        }
        
        return false;
    }
    
    public function hasAutoIncludeFile() {
        return file_exists($this->makeAutoIncludeFilePath());
    }
    
    public function createAutoIncludeFile() {
        if ($this->isCreated() && $this->hasAutoIncludeFile() === false) {
            return file_put_contents($this->makeAutoIncludeFilePath(), $this->loadResourceTemplate('autoinclude', '.php'));
        }
        
        return false;
    }
    
    public function getAutoIncludeFilePath() {
        if ($this->hasAutoIncludeFile()) {
            return $this->makeAutoIncludeFilePath();
        }
        
        return false;
    }
    
    public function deleteAutoIncludeFile() {
        if ($this->hasAutoIncludeFile()) {
            return unlink($this->makeAutoIncludeFilePath());
        }
        
        return false;
    }
    
    private function makeConfigurationFilePath() {
        return $this->getPath() . '/config.php';
    }
    
    private function makeAutoIncludeFilePath() {
        return $this->getPath() . '/autoinclude.php';
    }
    
}
