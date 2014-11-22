<?php

class ObjectMgr extends \ls\model\LSModelMgr {
    
    private $columns = array( 'id', 'Name' );
    
    /**
     * @return ObjectMgr
     */
    public static function instance() {
        if (!whas('Manager:ObjectMgr')) {
            wput('Manager:ObjectMgr', new ObjectMgr());
        }
        
        return wget('Manager:ObjectMgr');
    }
    
    public function __construct() {
        parent::__construct();
    }

    public function getTableName() {
        return 'Objects';
    }

    public function getPublicKeyName() {
        return 'id';
    }
    
    public function columns() {
        return $this->columns;
    }
    
    /**
     * @return ObjectFactory
     */
    public function factory() {
        return ObjectFactory::instance();
    }

//{CustomCodeBegin}

    

//{CustomCodeEnd}

}
