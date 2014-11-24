<?php

namespace ls\internal;

abstract class Servlet extends Resource {
    
    /**
     *
     * @var type 
     */
    private $attachedOperations = array();
    
    /**
     * 
     * @param type $method
     * @param type $args
     * @return type
     */
    public function __call($method, $args) {
        if (!isset($this->$method)) {
            if (isset($this->attachedOperations[$method])) {
                return $this->scriptlet($this->attachedOperations[$method]);
            }
        } else {
            return parent::__call($method, $args);
        }
    }
    
    /**
     * 
     */
    public abstract function init();
    
    /**
     * 
     * @param string $name
     */
    public function useLib($name) {
        $this->useResource($name, 'lib');
    }
    
    /**
     * 
     * @param string $name
     */
    public function scriptlet($name, $args = null) {
        return $this->useResource($name, 'scriptlet', $args);
    }
    
    /**
     * 
     * @param string $name
     * @throws ResourceNotFoundException
     */
    public function page($name, $args = null) {
        $resourceInfo = $this->getApplication()->locateResource($name, 'page', '.php');
        
        if ($resourceInfo !== false) {
            $page = new Page($resourceInfo->getName(), $resourceInfo->getContainer(), $args);
            $page->loadPageFile();
            exit;
        } else {
            throw new ResourceNotFoundException('Page ' . $name . ' not found');
        }
    }
    
    /**
     * 
     * @param type $name
     * @param type $scriptletName
     */
    public function attachOperation($name, $scriptletName) {
        $this->attachedOperations[$name] = $scriptletName;
    }
    
    /**
     * 
     * @param string $name
     * @param string $type
     * @param string $extension
     * @throws ResourceNotFoundException
     */
    public function useResource($name, $type, $args = null, $extension = '.php') {
        $resourceInfo = $this->getApplication()->locateResource($name, $type, $extension);
        
        if ($resourceInfo !== false) {
            if ($args === null) {
                $args = array();
            }
            
            extract($args);
            return (require $resourceInfo->getPath());
        } else {
            throw new ResourceNotFoundException(ucfirst($type) . ' ' . $name . ' not found');
        }
    }
    
}
