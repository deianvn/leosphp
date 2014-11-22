<?php

class SampleFactory extends \ls\model\LSModelFactory {
    
    /**
     * @return SampleFactory
     */
    public static function instance() {
        if (!whas('Factory:SampleFactory')) {
            wput('Factory:SampleFactory', new SampleFactory());
        }
        
        return wget('Factory:SampleFactory');
    }
    
    public function __construct() {
        parent::__construct();
    }
    
    public function createObject() {
        return new Sample();
    }

}
