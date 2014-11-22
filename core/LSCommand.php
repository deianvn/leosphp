<?php

namespace ls\core;

class LSCommand extends LSCommon {
    
    protected function scriptlet($name) {
        $path = getScriptletPath($name);
        
        if ($path !== null) {
            require $path;
            clearContext();
        }
    }
    
    protected function page($name) {
        new LSPage($name);
        exit(0);
    }
    
}
