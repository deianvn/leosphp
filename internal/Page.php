<?php

namespace ls\internal;

class Page extends Resource {
    
    const TYPE = 'page';
    
    const EXT = '.php';
    
    private $args;
    
    public function __construct($name, $container, $args) {
        parent::__construct($name, self::TYPE, $container, self::EXT);
        $this->args = $args;
    }

    public function pagelet($name, $args = null) {
        $resourceInfo = $this->getApplication()->locateResource($name, 'pagelet', '.php');
        
        if ($resourceInfo !== false) {
            if ($args === null) {
                $args = array();
            }
            
            extract($args);
            require $resourceInfo->getPath();
        } else {
            throw new ResourceNotFoundException('Pagelet ' . $name . ' not found');
        }
    }
    
    public function loadPageFile() {
        if ($this->args === null) {
            $this->args = array();
        }
        
        extract($this->args);
        require $this->getPath();
    }
    
}
