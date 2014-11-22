<?php

class AttributeMgr extends \ls\model\LSModelMgr {
    
    private $columns = array( 'id', 'Name', 'ObjectID' );
    
    /**
     * @return AttributeMgr
     */
    public static function instance() {
        if (!whas('Manager:AttributeMgr')) {
            wput('Manager:AttributeMgr', new AttributeMgr());
        }
        
        return wget('Manager:AttributeMgr');
    }
    
    public function __construct() {
        parent::__construct();
    }

    public function getTableName() {
        return 'Attributes';
    }

    public function getPublicKeyName() {
        return 'id';
    }
    
    public function columns() {
        return $this->columns;
    }
    
    /**
     * @return AttributeFactory
     */
    public function factory() {
        return AttributeFactory::instance();
    }

//{CustomCodeBegin}

    

//{CustomCodeEnd}

}
