<?php

namespace ls\internal;

class Page extends Resource {
    
    const TYPE = 'page';
    
    const EXT = '.php';
    
    public function __construct($name, $container) {
        parent::__construct($name, self::TYPE, $container, self::EXT);
    }

    public function pagelet($name) {
        $resourceInfo = $this->getApplication()->locateResource($name, 'pagelet', '.php');
        
        if ($resourceInfo !== false) {
            require $resourceInfo->getPath();
        } else {
            throw new ResourceNotFoundException('Pagelet ' . $name . ' not found');
        }
    }
    
    public function loadPageFile() {
        require $this->getPath();
    }
    
}
