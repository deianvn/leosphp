<?php

class AttributeFactory extends \ls\model\LSModelFactory {
    
    /**
     * @return AttributeFactory
     */
    public static function instance() {
        if (!whas('Factory:AttributeFactory')) {
            wput('Factory:AttributeFactory', new AttributeFactory());
        }
        
        return wget('Factory:AttributeFactory');
    }
    
    public function __construct() {
        parent::__construct();
    }
    
    public function createObject() {
        return new Attribute();
    }

}
