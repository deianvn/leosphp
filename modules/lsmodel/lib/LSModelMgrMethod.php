<?php

namespace ls\model;

abstract class LSModelMgrMethod {
    /**
     *
     * @var LSModelMgr
     */
    private $parent;
    
    protected $db;
    
    public function __construct($parent) {
        $this->parent = $parent;
        $this->db = wget('DB');
    }
    
    /**
     * 
     * @return LSModelMgr
     */
    protected function getParent() {
        return $this->parent;
    }
    
    protected function clearObjectState(&$object) {
        $columns = $this->getParent()->columns();
        
        foreach ($columns as $column) {
            $object->attributeClearChangeState($column);
        }
    }
    
}
