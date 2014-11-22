<?php

namespace ls\model;

class LSModelMgrGetPackage {
    
    private $manager;
    private $cols;
    
    public function __construct($manager) {
        $this->manager = $manager;
    }
    
    public function prepareCols($cols = null) {
        if ($this->cols === null) {
            if ($cols === null) {
                $this->cols = array();
            } else {
                $this->cols = $cols;
            }
        }
    }
    
    public function addCol($name) {
        $this->cols[] = $name;
    }
    
    public function skip($name) {
        if(($key = array_search($name, $this->cols)) !== false) {
            unset($this->cols[$key]);
        }
    }
    
}
