<?php

namespace ls\internal;

abstract class ResourceContainer extends Container {
    
    /**
     * 
     * @param string $name
     * @return boolean
     */
    public function hasResourceType($name) {
        return file_exists(makeResourceTypePath($name));
    }
    
    /**
     * 
     * @param string $name
     * @return boolean
     */
    public function createResourceType($name) {
        if ($this->isBuilt() && $this->hasResourceFolder($name) === false) {
            return mkdir(makeResourceTypePath($name));
        }
        
        return false;
    }
    
    /**
     * 
     * @param string $name
     * @return boolean
     */
    public function deleteResourceType($name) {
        if ($this->hasResourceType($name)) {
            rrmdir($this->makeResourceTypePath($name));
            return $this->hasResourceType($name);
        }
        
        return false;
    }
    
    /**
     * 
     * @param string $name
     * @param string $type
     * @return boolean
     */
    public function hasResource($name, $type, $extension = '.php') {
        return file_exists($this->makeResourcePath($name, $type, $extension));
    }
    
    /**
     * 
     * @param string $name
     * @param string $type
     * @return boolean
     */
    public function createResource($name, $type, $extension) {
        if ($this->isBuilt() && $this->hasResourceType($type) && $this->hasResource($name, $type, $extension) === false) {
            return file_put_contents($this->makeResourcePath($name, $type, $extension), $this->loadResourceTemplate($type, $extension));
        }
        
        return false;
    }
    
    /**
     * 
     * @param string $name
     * @param string $type
     * @return boolean
     */
    public function deleteResource($name, $type, $extension = '.php') {
        if ($this->hasResource($name, $type, $extension)) {
            return unlink($this->makeResourcePath($name, $type, $extension));
        }
        
        return false;
    }
    
    /**
     * 
     * @param string $name
     * @param string $type
     */
    public function requireResource($name, $type, $extension = '.php') {
        require $this->makeResourcePath($name, $type, $extension);
    }
    
    /**
     * 
     * @param string $name
     * @param string $type
     */
    public function requireOnceResource($name, $type, $extension = '.php') {
        require_once $this->makeResourcePath($name, $type, $extension);
    }
    
    /**
     * 
     * @param string $name
     * @param string $type
     */
    public function includeResource($name, $type, $extension = '.php') {
        include $this->makeResourcePath($name, $type, $extension);
    }
    
    /**
     * 
     * @param string $name
     * @param string $type
     */
    public function includeOnceResource($name, $type, $extension = '.php') {
        include_once $this->makeResourcePath($name, $type, $extension);
    }
    
    /**
     * 
     * @param string $name
     * @param string $type
     * @return boolean
     */
    public function getResourcePath($name, $type, $extension = '.php') {
        if ($this->hasResource($name, $type, $extension)) {
            return $this->makeResourcePath($name, $type, $extension);
        }
        
        return false;
    }
    
    /**
     * 
     * @param string $name
     * @return string
     */
    private function makeResourceTypePath($name) {
        return $this->getPath() . $name . '/';
    }
    
    /**
     * 
     * @param string $name
     * @param string $type
     * @return string
     */
    private function makeResourcePath($name, $type, $extension = '.php') {
        return $this->makeResourceTypePath($type) . $name . $extension;
    }
    
    /**
     * 
     * @param string $type
     * @param string $extension
     * @return string
     */
    private function loadResourceTemplate($type, $extension = '.php') {
        $path = BASE_DIR . 'internal/templates/' . $type . $extension . '.template';
        $content = file_get_contents($path);
        
        if ($content === false) {
            $content = "\n";
        }
        
        return $content;
    }
    
}
