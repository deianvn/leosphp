<?php

namespace ls\internal;

abstract class Resource {
    
    /**
     *
     * @var string 
     */
    private $name;
    
    /**
     *
     * @var string 
     */
    private $type;
    
    /**
     *
     * @var ls\internal\Container 
     */
    private $container;
    
    /**
     *
     * @var string 
     */
    private $extension;
    
    /**
     * 
     * @param string $name
     * @param string $type
     * @param ls\internal\Container $container
     * @param string $extension
     */
    public function __construct($name, $type, $container, $extension = '.php') {
        $this->name = $name;
        $this->type = $type;
        $this->container = $container;
        $this->extension = $extension;
    }
    
    /**
     * 
     * @return stirng
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * 
     * @return string
     */
    public function getType() {
        return $this->type;
    }
    
    /**
     * 
     * @return ls\internal\Container
     */
    public function getContainer() {
        return $this->container;
    }
    
    /**
     * 
     * @return ls\internal\Application
     */
    public function getApplication() {
        if ($this->container instanceof Application) {
            return $this->container;
        } else {
            return $this->container->getApplication();
        }
    }
    
    /**
     * 
     * @return string
     */
    public function getExtension() {
        return $this->extension;
    }
    
    /**
     * 
     * @return string
     */
    public function getPath() {
        return $this->getContainer()->getPath() . $this->getType() . '/' . $this->getName() . $this->getExtension();
    }
    
}
