<?php

namespace ls\internal;

abstract class Command extends Servlet {
    
    const TYPE = 'command';
    
    const EXT = '.php';
    
    /**
     * 
     * @param string $name
     * @param \ls\internal\Container $container
     */
    public function __construct($name, $container) {
        parent::__construct($name, self::TYPE, $container, self::EXT);
    }
    
    /**
     * 
     */
    public function init() {}
    
    /**
     * 
     */
    public abstract function printHelp();
    
}
