<?php

namespace ls\internal;

class Page extends Resource {
    
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
