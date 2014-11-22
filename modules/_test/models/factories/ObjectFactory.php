<?php

class ObjectFactory extends \ls\model\LSModelFactory {
    
    /**
     * @return ObjectFactory
     */
    public static function instance() {
        if (!whas('Factory:ObjectFactory')) {
            wput('Factory:ObjectFactory', new ObjectFactory());
        }
        
        return wget('Factory:ObjectFactory');
    }
    
    public function __construct() {
        parent::__construct();
    }
    
    public function createObject() {
        return new Object();
    }

}
