<?php

namespace ls\internal;

class WebAdapter {
    
    private $uri;
    
    private $router;
    
    private $ls;
    
    public function __construct($uri) {
        $this->uri = $uri;
        $this->createLs();
        $this->createRouter();
    }
    
    private function createLs() {
        $this->ls = new LS();
        $this->ls->loadConfigurationFile();
    }
    
    private function createRouter() {
        $this->router = new Router($this->ls);
        $this->router->loadRoutesFile();
    }
    
    private function route() {
        $this->uri = $this->router->route($this->uri);
    }

    
}
