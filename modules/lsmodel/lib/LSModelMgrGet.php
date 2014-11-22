<?php

namespace ls\model;
use \ls\core\LSLogger as LSLogger;

class LSModelMgrGet extends LSModelMgrMethod {
    
    private $statement;
    private $condition;
    private $limit;
    private $order;
    private $method;
    private $query;
    private $extraNumber;
    private $packages;
    private $packageIndex;
    
    function __construct($parent) {
        parent::__construct($parent);
    }
    
    public function get($method = 'array') {
        $this->packages = array(new LSModelMgrGetPackage($this));
        $this->packageIndex = 0;
        $this->cols = null;
        $this->condition = null;
        $this->limit = null;
        $this->order = null;
        $this->query = null;
        $this->extraNumber = null;
        
        if ($method !== 'array' && $method !== 'first' && $method !== 'count' && $method !== 'bool' && $method !== 'fields') {
            return false;
        }
        
        $this->method = $method;
        
        return $this;
    }
    
    public function join($manager) {
        $this->packages[] = new LSModelMgrGetPackage($manager);
        $this->packageIndex++;
        
        return $this;
    }
    
    public function query($query) {
        $this->query = $query;
        
        return $this;
    }
    
    public function col($name) {
        $this->getCurrentPackage()->prepareCols();
        $this->getCurrentPackage()->addCol($name);
        
        return $this;
    }
    
    public function extra($number) {
        $this->extraNumber = $number;
        return $this;
    }
    
    public function skip($name) {
        $this->getCurrentPackage()->prepareCols($this->getParent()->columns());
        $this->getCurrentPackage()->skip($name);
        
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
            $p2 = $p2 === true ? 'TRUE' : 'FALSE';
        } else if ($p2 instanceof LSSQLFunc) {
            $p2 = $this->getParent()->escape($p2->func);
        } else {
            $p2 = $this->getParent()->escape($p2);
        }
        
        $this->condition .= ' ' . $p1 . ' ' . $p2;
        
        return $this;
    }
    
    public function orderby($column, $direction = null) {
        
        if ($this->order === null) {
            $this->order = ' ORDER BY';
        } else {
            $this->order .= ',';
        }
        
        $this->order .= ' ' . $column;
        
        if ($direction !== null) {
            $this->order .= ' ' . $direction;
        }
        
        return $this;
    }
    
    public function limit($p1, $p2 = null) {
        
        $this->limit = ' LIMIT ' . $p1;
        
        if ($p2 !== null) {
            $this->limit .= ', ' . $p2;
        }
        
        return $this;
    }
    
    public function submit() {
        
        if ($this->query !== null) {
            $this->statement .= $this->query;
        } else {
            $fromStatment = $this->getParent()->getTableName();
            
            if (count($this->packages) > 1) {
                
            }
            
            if ($this->method === 'count') {
                $this->statement = 'SELECT COUNT(*) FROM ' . $this->getParent()->getTableName();
            } else {
                $this->statement = 'SELECT * FROM ' . $this->getParent()->getTableName();
            }

            if ($this->cols !== null && count($this->cols > 0)) {
                $colsStatement = null;

                foreach ($this->cols as $column) {
                    if ($colsStatement !== null) {
                        $colsStatement .= ', ';
                    }

                    $colsStatement .= $column;
                }

                $this->statement = str_replace('*', $colsStatement, $this->statement);
            }

            if ($this->condition !== null) {
                $this->statement .= $this->condition;
            }

            if ($this->order !== null) {
                $this->statement .= $this->order;
            }

            if ($this->limit !== null) {
                $this->statement .= $this->limit;
            }
        }
        
        $result = $this->db->query($this->statement);
        
        if ($result === false) {
            LSLogger::warn('Unsuccessful query: ' . $this->statement);
            
            return false;
        }
        
        if ($this->method === 'array') {
            $objects = array();
            
            while (($row = dbFetchRow($result)) !== null) {
                $object = $this->createObjectFromArray($row);
                
                if ($object !== false) {
                    $this->clearObjectState($object);
                }
                
                $objects[] = $object;
            }
            
            $this->db->freeResult($result);
            
            return $objects;
        } else if ($this->method === 'first') {
            $row = $this->db->fetchRow($result);
            
            if ($row === null) {
                return false;
            }
            
            $object = $this->createObjectFromArray($row);            
            $this->db->freeResult($result);
            
            if ($object !== false) {
                $this->clearObjectState($object);
            }
            
            return $object;
        } else if ($this->method === 'bool') {
            $row = $this->db->fetchRow($result);
            
            if ($row === null) {
                return false;
            }
            
            return true;
        } else if ($this->method === 'count') {
            $row = $this->db->fetchRow($result);
            
            if ($row === null) {
                return false;
            }
            
            return $row[0];
        } else if ($this->method === 'fields') {
            $fields = array();
            $number = 0;
            
            if ($this->extraNumber !== null) {
                $number = $this->extraNumber;
            }
            
            while (($row = dbFetchRow($result)) !== null) {
                $object = $this->createObjectFromArray(array_slice($row, $number));
                $this->clearObjectState($object);
                $r = array();
                
                for ($i = 0; $i < $number; $i++) {
                    $r[] = $row[$i];
                }
                
                $r[] = $object;                
                $fields[] = $r;
            }
            
            $this->db->freeResult($result);
            
            return $fields;
        }
        
        return false;
                
    }
    
    private function createObjectFromArray($row) {
        if ($this->cols !== null) {
            return $this->createObjectFromInconsistentArray($row);
        }
        
        return $this->getParent()->createObjectFromArray($row);
    }
    
    private function createObjectFromInconsistentArray($row) {
        $columns = $this->getParent()->columns();
        $clearColumns = array();
        $length = count($columns);
        $rowi = 0;
        
        for ($i = 0; $i < $length; $i++) {
            if(array_search($columns[$i], $this->cols) === false) {
                $clearColumns[] = $columns[$i];
                $columns[$i] = null;
            } else {
                $columns[$i] = $row[$rowi];
                $rowi++;
            }
            
        }
        
        $object = $this->getParent()->createObjectFromArray($columns);
        
        foreach ($clearColumns as $column) {
            $object->attributeEmpty($column);
        }
        
        return $object;
    }
    
    private function getCurrentPackage() {
        $package = $this->packages[$this->packageIndex];
        return $package;
    }
    
}
