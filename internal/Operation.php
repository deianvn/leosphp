<?php

namespace ls\internal;

class Operation {
    
    private $name;
    
    private $scriptletName;
    
    private $args;
    
    public function __construct($name, $scriptletName, $args) {
        $this->name = $name;
        $this->scriptletName = $scriptletName;
        $this->args = $args;
    }
    
    public function getName() {
        return $this->name;
    }

    public function getScriptletName() {
        return $this->scriptletName;
    }

    public function getArgs() {
        return $this->Args;
    }
    
}
