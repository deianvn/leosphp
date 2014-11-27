<?php

namespace ls\orm;

class ModelDescriptor {
    
    private $modelDatabaseTableName;
    
    private $modelDatabaseEngine;
    
    private $modelDatabaseCharset;
    
    private $modelPackage;
    
    private $modelName;
    
    private $modelAttributes;
    
    public function __construct() {
        $this->modelDatabaseEngine = 'InnoDB';
        $this->modelDatabaseCharset = 'UTF8';
    }
    
}
