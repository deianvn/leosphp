<?php

namespace ls\internal;

abstract class ResourceContainer extends Container {
    
    private $defaultResourceTypes = array('webroot', 'lib', 'command', 'action', 'scriptlet', 'page', 'pagelet', 'template');
    
    /**
     * 
     * @param boolean $addDefaultResourceTypes
     * @param string[] $resourceTypes
     * @return type
     */
    public function create($addDefaultResourceTypes = true, $resourceTypes = null) {
        $result = parent::create();
        
        if ($addDefaultResourceTypes === true) {
            $resourceTypes = $resourceTypes === null ? $this->defaultResourceTypes : array_merge($this->defaultResourceTypes, $resourceTypes);
        }
        
        if ($resourceTypes !== null) {
            foreach ($resourceTypes as $resourceType) {
                $result = $result && $this->createResourceType($resourceType);
            }
        }
        
        return $result;
    }
    
    /**
     * 
     * @param string $name
     * @return boolean
     */
    public function hasResourceType($name) {
        return file_exists($this->makeResourceTypePath($name));
    }
    
    /**
     * 
     * @param string $name
     * @return boolean
     */
    public function createResourceType($name) {
        if ($this->isCreated() && $this->hasResourceType($name) === false) {
            return mkdir($this->makeResourceTypePath($name));
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
     * @param string $type
     * @param string $extension
     * @return string
     */
    protected function loadResourceTemplate($type, $extension) {
        $resourceInfo = $this->getApplication()->locateResource($type . $extension, 'template', '.template');
        
        if ($resourceInfo !== false) {
            $content = file_get_contents($resourceInfo->getPath());

            if ($content !== false) {
                return $content;
            }
        }
        
        return "\n";
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
    
}
