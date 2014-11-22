<?php

namespace ls\model;
use \ls\core\LSLogger as LSLogger;

class LSModelMgrCreate extends LSModelMgrMethod {
    
    private $method;
    private $statement;
    private $row;
    
    function __construct($parent) {
        parent::__construct($parent);
    }
    
    public function create($method = 'bool') {
        $this->row = null;
        
        if ($method !== 'bool' && $method !== 'id' && $method !== 'object') {
            return false;
        }
        
        $this->method = $method;
        $this->statement = 'INSERT INTO ' . $this->getParent()->getTableName();
        
        return $this;
    }
    
    public function object($object, $skipPublicKey = true) {
        foreach ($object->toAssoc() as $key => $value) {
            if (($skipPublicKey === true && $this->getParent()->getPublicKeyName() === $key) || $object->attributeIsEmpty($key)) {
                continue;
            }
            
            $this->set($key, LSDataConverter::convertBack($object->attributeGetType($key), $value));
        }
        
        return $this;
    }
    
    public function set($p1, $p2) {
        if ($this->row === null) {
            $this->row = array();
        }
        
        if ($p2 === null) {
            $this->row[$p1] = 'NULL';
        } else if (is_string($p2)) {
            $this->row[$p1] = "'" . $this->getParent()->escape($p2) . "'";
        } else if (is_bool($p2)) {
            $this->row[$p1] = $p2 === true ? 'TRUE' : 'FALSE';
        } else if ($p2 instanceof LSSQLFunc) {
            $this->row[$p1] = $this->getParent()->escape($p2->func);
        } else {
            $this->row[$p1] = $this->getParent()->escape($p2);
        }
        
        return $this;
    }
    
    public function submit() {
        if ($this->row === null) {
            return false;
        }
        
        $values = null;
        $keys = null;
        
        foreach ($this->row as $key => $value) {
            if ($keys != null) {
                $keys .= ', ';
            }
            
            if ($values != null) {
                $values .= ', ';
            }
            
            $keys .= $key;            
            $values .= $value;
        }
        
        $this->statement .= ' (' . $keys . ') VALUES(' . $values . ')';
        $result = $this->db->query($this->statement);
        
        if ($this->method === 'id') {
            $result = $this->db->lastGeneratedID();
        } else if ($this->method === 'object') {
            $id = $this->db->lastGeneratedID();
            $result = $this->parent->get('first')->condition($this->parent->getPublicKeyName(), $id);
        }
        
        return $result;
    }
    
}
