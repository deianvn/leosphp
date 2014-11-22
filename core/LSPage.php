<?php

namespace ls\core;

class LSPage extends LSCommon {
    
    public function __construct($name) {
        $path = page($name);
        
        if ($path !== null) {
            require $path;
            clearContext();
        }
        
    }
    
    protected function snippet($name) {
        $path = snippet($name);
        
        if ($path !== null) {
            require $path;
            clearContext();
        }
    }
    
    protected function ll($key) {
        ll($key);
    }
    
}
