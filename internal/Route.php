<?php

namespace ls\internal;

class Route {
    
    const varPattern = '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*';
    
    private $uri;
    
    private $target;
    
    private $matchers;
    
    private $converters;
    
    private $filters;
    
    private $pattern;
    
    public function __construct($uri, $target) {
        $this->uri = $uri;
        $this->target = $target;
        $this->matchers = array();
    }
    
    public function match($matchers) {
        $this->matchers = $matchers;
        return $this;
    }
    
    public function convert($converters) {
        $this->converters = $converters;
        return $this;
    }
    
    public function filter($filters) {
        $this->filters = $filters;
        return $this;
    }
    
    public function build() {
        
    }
    
}
