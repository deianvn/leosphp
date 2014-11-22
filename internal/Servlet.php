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
    public function scriptlet($name) {
        $this->useResource($name, 'scriptlet');
    }
    
    /**
     * 
     * @param string $name
     * @throws ResourceNotFoundException
     */
    public function page($name) {
        $resourceInfo = $this->getApplication()->locateResource($name, 'page', '.php');
        
        if ($resourceInfo !== false) {
            $page = new Page($resourceInfo->getName(), $resourceInfo->getType(), $resourceInfo->getContainer(), $resourceInfo->getExtension());
            $page->loadPageFile();
            exit;
        } else {
            throw new ResourceNotFoundException('Page ' . $name . ' not found');
        }
    }
    
    /**
     * 
     * @param string $scriptletName
     * @param string $moduleName
     */
    public function registerOperation($scriptletName, $moduleName = null) {
        
    }
    
    /**
     * 
     * @param string $name
     * @param string $type
     * @param string $extension
     * @throws ResourceNotFoundException
     */
    private function useResource($name, $type, $extension = '.php') {
        $resourceInfo = $this->getApplication()->locateResource($name, $type, $extension);
        
        if ($resourceInfo !== false) {
            require $resourceInfo->getPath();
        } else {
            throw new ResourceNotFoundException(ucfirst($type) . ' ' . $name . ' not found');
        }
    }
    
}
