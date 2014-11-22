<?php

namespace ls\core;

class LSContext {
    
    private $containerType;
    private $containerName;
    
    public function __construct($containerType, $containerName) {
        $this->containerType = $containerType;
        $this->containerName = $containerName;
    }

    function getContainerType() {
        return $this->containerType;
    }

    function getContainerName() {
        return $this->containerName;
    }
    
}