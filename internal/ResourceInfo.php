<?php

namespace ls\internal;

class ResourceInfo {
    
    private $name;
    
    private $type;
    
    private $extension;
    
    private $container;
    
    public function __construct($name, $type, $container, $extension = '.php') {
        $this->name = $name;
        $this->type = $type;
        $this->container = $container;
        $this->extension = $extension;
    }
    
    public function getName() {
        return $this->name;
    }

    public function getType() {
        return $this->type;
    }

    public function getExtension() {
        return $this->extension;
    }

    public function getContainer() {
        return $this->container;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setExtension($extension) {
        $this->extension = $extension;
    }

    public function setContainer($container) {
        $this->container = $container;
    }
    
    public function getPath() {
        return $this->getContainer()->getResourcePath($this->getName(), $this->getType(), $this->getExtension());
    }
    
}
