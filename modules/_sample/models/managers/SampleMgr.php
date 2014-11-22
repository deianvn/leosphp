<?php

class SampleMgr extends \ls\model\LSModelMgr {
    
    private $columns = array( 'id', 'Name' );
    
    /**
     * @return SampleMgr
     */
    public static function instance() {
        if (!whas('Manager:SampleMgr')) {
            wput('Manager:SampleMgr', new SampleMgr());
        }
        
        return wget('Manager:SampleMgr');
    }
    
    public function __construct() {
        parent::__construct();
    }

    public function getTableName() {
        return 'Samples';
    }

    public function getPublicKeyName() {
        return 'id';
    }
    
    public function columns() {
        return $this->columns;
    }
    
    /**
     * @return SampleFactory
     */
    public function factory() {
        return SampleFactory::instance();
    }

//{CustomCodeBegin}

    

//{CustomCodeEnd}

}
