<?php

namespace ls\model;

abstract class LSModelMgr {
    
    private $db;
    private $dbget;
    private $dbcreate;
    private $dbupdate;
    private $dbdelete;
    
    public function __construct() {
        $this->db = wget('DB');
        $this->dbget = new LSModelMgrGet($this);
        $this->dbcreate = new LSModelMgrCreate($this);
        $this->dbupdate = new LSModelMgrUpdate($this);
        $this->dbdelete = new LSModelMgrDelete($this);
    }
    
    protected abstract function getTableName();
    
    protected abstract function columns();
    
    protected abstract function getPublicKeyName();
    
    /**
     * return LSModelFactory
     */
    public abstract function factory();
    
    public function createObject() {
        return $this->factory()->createObject();
    }
    
    public function columnFullName($column) {
        if (in_array($column, $this->columns()) === false) {
            return false;
        }
        
        return $this->getTableName() . '.' . $column;
    }
    
    public function columnsAsString($full = false, $skip = null) {
        $prefix = '';
        
        if ($full === true) {
            $prefix = $this->getTableName() . '.';
        }
        
        $cols = '';
        
        foreach ($this->columns() as $column) {
            if ($skip !== null && in_array($column, $skip)) {
                continue;
            }
            
            if ($cols !== '') {
                $cols .= ', ';
            }
            
            $cols .= $prefix . $column;
        }
        
        return $cols;
    }
    
    public function createArrayFromObject($object) {
        return $object->toArray();
    }
    
    public function createAssocFromObject($object) {
        return $object->toAssoc();
    }
    
    public function createObjectFromArray($row) {
        $object = $this->createObject();
        $object->setFromArray($row);
        
        return $object;
    }
    
    public function createObjectFromAssoc($row) {
        $object = $this->createObject();
        $object->setFromAssoc($row);
        
        return $object;
    }
    
    public function escape($str) {
        return $this->db->esc($str);
    }
    
    /**
     * 
     * @param type $method
     * @return LSModelMgrGet
     */
    public function get($method = 'array') {
        $this->connect();
        return $this->dbget->get($method);
    }
    
    /**
     * 
     * @param type $method
     * @return LSModelMgrCreate
     */
    public function create($method = 'bool') {
        $this->connect();
        return $this->dbcreate->create($method);
    }
    
    /**
     * 
     * @return LSModelMgrUpdate
     */
    public function update() {
        $this->connect();
        return $this->dbupdate->update();
    }
    
    /**
     * 
     * @return LSModelMgrDelete
     */
    public function delete() {
        $this->connect();
        return $this->dbdelete->delete();
    }
    
    public function beginTransaction() {
        $this->db->begin();
    }
    
    public function commitTransaction() {
        $this->db->commit();
    }
    
    public function rollbackTransaction() {
        $this->db->rollback();
    }
    
    private function connect() {
        if ($this->db->isActive() === false) {
            return $this->db->connect(cget('DBHost'), cget('DBUserName'), cget('DBPassword'), cget('DBName'));
        }
        
        return true;
    }
    
}
