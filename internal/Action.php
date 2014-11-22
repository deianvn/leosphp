<?php

namespace ls\internal;

abstract class Action extends Servlet {
    
    const TYPE = 'action';
    
    const EXT = '.php';
    
    public function __construct($name, $container) {
        parent::__construct($name, self::TYPE, $container, self::EXT);
    }

}
