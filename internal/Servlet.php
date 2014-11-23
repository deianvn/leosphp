<?php

namespace ls\internal;

abstract class Servlet extends Resource {
    
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
        $this->useResource($name, 'scriptlet', $args);
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
     * @param type $scriptletName.
     */
    public function attachOperation($name, $scriptletName) {
        
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
            require $resourceInfo->getPath();
        } else {
            throw new ResourceNotFoundException(ucfirst($type) . ' ' . $name . ' not found');
        }
    }
    
}
