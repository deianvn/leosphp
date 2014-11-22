<?php

namespace ls\core;

class LSRoute {
    
    private $chunks;
    
    private $chunksCount;
    
    private $scheme;
    
    public static function create() {
        return new LSRoute();
    }
    
    public function chunks($chunks) {
        if (is_array($chunks)) {
            $this->chunks = $chunks;
            $this->chunksCount = count($chunks);
        }
        
        return $this;
    }
    
    public function scheme($scheme) {
        $this->scheme = $scheme;
        
        return $this;
    }
    
    public function match($url) {
        $parts = explode('/', $url);
        $valid = false;
        
        if (count($parts) === $this->chunksCount) {
            $valid = true;
            
            for ($i = 0; $i < $this->chunksCount; $i++) {
                if (preg_match('/^' . $this->chunks[$i] . '$/', $parts[$i]) === 0) {
                    $valid = false;
                    break;
                }
            }
        }
        
        return $valid;
    }
    
    public function build($url) {
        $parts = explode('/', $url);
        $partsCount = count($parts);
        $search = array();
        
        for ($i = 0; $i < $partsCount; $i++) {
            $search[] = '$' . ($i + 1);
        }
        
        return str_replace($search, $parts, $this->scheme);
    }
    
}
