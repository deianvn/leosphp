<?php

namespace ls\model;
use \ls\core\LSLogger as LSLogger;

class LSModelMgrUpdate extends LSModelMgrMethod {
    
    private $statement;
    private $condition;
    private $row;
    private $object;
    
    function __construct($parent) {
        parent::__construct($parent);
    }
    
    public function update() {
        $this->condition = null;
        $this->row = null;
        $this->statement = 'UPDATE ' . $this->getParent()->getTableName();
        
        return $this;
    }
    
    public function object(&$object, $skipPublicKey = true) {
        foreach ($object->toAssoc() as $key => $value) {
            $isAttributeEmpty = $object->attributeIsEmpty($key);
            
            if ($this->getParent()->getPublicKeyName() === $key) {
                $this->condition($this->getParent()->getPublicKeyName() . ' =', $value);
                
                if ($skipPublicKey === true || $isAttributeEmpty) {
                    continue;
                }
            }
            
            if (!$isAttributeEmpty && $object->attributeIsChanged($key)) {
                $this->set($key, LSDataConverter::convertBack($object->attributeGetType($key), $value));
            }
        }
        
        $this->object = &$object;
        
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
    
    public function condition($p1, $p2) {
        
        if ($this->condition === null) {
            $this->condition = 'WHERE';
        }
        
        if ($p2 === null) {
            $p2 = 'NULL';
        } else if (is_string($p2)) {
            $p2 = "'" . $this->getParent()->escape($p2) . "'";
        } else if (is_bool($p2)) {
            $p2 = $p2 === true ? 'TRUE' : 'FALSE';
        } else if ($p2 instanceof LSSQLFunc) {
            $p2 = $this->getParent()->escape($p2->func);
        } else {
            $p2 = $this->getParent()->escape($p2);
        }
        
        $this->condition .= ' ' . $p1 . ' ' . $p2;
        
        return $this;
    }
    
    public function submit() {
        if ($this->row === null) {
            return false;
        }
        
        $elements = null;
        
        foreach ($this->row as $key => $value) {
            if ($elements !== null) {
                $elements .= ', ';
            }
            
            $elements .= $key . ' = ' . $value;
        }
        
        $this->statement .= ' SET ' . $elements;
        
        if ($this->condition !== null) {
            $this->statement .= ' ' . $this->condition;
        }
        
        $result = $this->db->query($this->statement);
        
        if ($result === false) {
            LSLogger::warn('Unsuccessful query:' . PHP_EOL . $this->statement);
        } else if ($this->object !== null) {
            $this->clearObjectState($this->object);
        }
        
        return $result;
    }
    
}
