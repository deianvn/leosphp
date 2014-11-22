<?php

namespace ls\internal;

class Router {
    
    private $applicationAliases;
    
    private $actionAliases;
    
    function __construct() {
        $this->applicationAliases = array();
        $this->actionAliases = array();
    }
    
}
