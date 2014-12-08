<?php

namespace ls\orm;

class AttributeDescriptor {
    
    private $dbType = 'text';
    
    private $mappedType = 'string';
    
    private $publicKey = false;
    
    private $uniqueKey = false;
    
    public function createAttribute() {
        
    }
    
    public function type($dbType, $mappedType = 'string') {
        $this->dbType = $dbType;
        $this->mappedType = $mappedType;
        return $this;
    }
    
    public function publicKey($publicKey = true) {
        $this->publicKey = $publicKey;
        return $this;
    }
    
    public function uniqueKey($uniqueKey = true) {
        $this->uniqueKey = $uniqueKey;
        return $this;
    }
    
}
