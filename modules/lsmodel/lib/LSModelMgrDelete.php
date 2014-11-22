<?php

namespace ls\model;
use \ls\core\LSLogger as LSLogger;

class LSModelMgrDelete extends LSModelMgrMethod {
    
    private $statement;
    private $condition;
    
    function __construct($parent) {
        parent::__construct($parent);
    }
    
    public function delete() {
        $this->condition = null;
        $this->statement = 'DELETE FROM ' . $this->getParent()->getTableName();
        
        return $this;
    }
    
    public function object($object) {
        $this->condition = '';
        $publicKeyName = $this->getParent()->getPublicKeyName();
        $assocObject = $object->toAssoc();
        $publicKey = LSDataConverter::convert($object->attributeGetType($publicKeyName), $assocObject[$publicKeyName]);
        $this->condition($this->getParent()->getPublicKeyName(), $publicKey);
        
        return $this;
    }
    
    public function condition($p1, $p2) {
        
        if ($this->condition === null) {
            $this->condition = ' WHERE';
        }
        
        if ($p2 === null) {
            $p2 = 'NULL';
        } else if (is_string($p2)) {
            $p2 = "'" . $this->getParent()->escape($p2) . "'";
        } else if (is_bool($p2)) {
            $this->row[$p1] = $p2 === true ? 'TRUE' : 'FALSE';
        } else if ($p2 instanceof LSSQLFunc) {
            $p2 = $this->getParent()->escape($p2->func);
        } else {
            $p2 = $this->getParent()->escape($p2);
        }
        
        $this->condition .= ' ' . $p1 . ' ' . $p2;
        
        return $this;
    }
    
    public function submit() {
        
        if ($this->condition !== null) {
            $this->statement .= ' ' . $this->condition;
        }
        
        $result = $this->db->query($this->statement);
        
        if ($result === false) {
            LSLogger::warn('Unsuccessful query:' . PHP_EOL . $this->statement);
        }
        
        return $result;
    }
    
}
