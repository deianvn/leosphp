<?php

use \ls\core\LSLogger as LSLogger;

class ModelMgr {
    
    private $errorMessage;
    
    public function getErrorMessage() {
        return $this->errorMessage;
    }
    
    public function generateModel($model, $module) {
        $path = INCLUDE_DIR . '/modules/' . $module . '/models/';
        $node = simplexml_load_file($path . $model . '.model');
        $attrs = $node->attributes();
        
        $model = new Model();
        
        if (!isset($attrs['name'])) {
            LSLogger::error('Attribute name not available in model: ' . $model . ' / ' . $module);
            return false;
        }
        
        $model->name = trim($attrs['name']);
        
        if (!isset($attrs['table'])) {
            LSLogger::error('Attribute table not available in model: ' . $model . ' / ' . $module);
            return false;
        }
        
        $model->table = trim($attrs['table']);
        
        if (isset($attrs['engine'])) {
            $model->engine = trim($attrs['engine']);
        }
        
        if (isset($attrs['charset'])) {
            $model->charset = trim($attrs['charset']);
        }
        
        if (isset($attrs['namespace'])) {
            $model->namespace = trim($attrs['namespace']);
        }
        
        $params = array();
        $constraints = array();
        
        foreach ($node->children() as $child) {
            $childAtts = $child->attributes();
            
            if ($child->getName() === 'parameter') {
                $param = new ModelParam();
                
                if (!isset($childAtts['name'])) {
                    LSLogger::error('Attribute name not available in model parameter: ' . $child->getName() . '/' . $model . ' / ' . $module);
                    return false;
                } else {
                    $param->name = trim($childAtts['name']);
                }
                
                if (!isset($childAtts['type'])) {
                    LSLogger::error('Attribute type not available in model parameter: ' . $child->getName() . '/' . $model . ' / ' . $module);
                    return false;
                } else {
                    $param->type = trim($childAtts['type']);
                }
                
                if (isset($childAtts['mappedtype'])) {
                    $param->mappedType = trim($childAtts['mappedtype']);
                } else {
                    $param->mappedType = 'string';
                }
                
                if (isset($childAtts['publickey'])) {
                    $param->publicKey = LSCast::strtob(trim($childAtts['publickey']));
                }
                
                if (isset($childAtts['uniquekey'])) {
                    $param->uniqueKey = LSCast::strtob(trim($childAtts['uniquekey']));
                }
                
                if (isset($childAtts['autoincrement'])) {
                    $param->autoIncrement = LSCast::strtob(trim($childAtts['autoincrement']));
                }
                
                if (isset($childAtts['notnull'])) {
                    $param->notNull = LSCast::strtob(trim($childAtts['notnull']));
                }
                
                if (isset($childAtts['default'])) {
                    $param->defaultValue = trim($childAtts['default']);
                }
                
                $params[] = $param;
            } else if ($child->getName() === 'uniquekey') {
                $uniqueKey = new ModelUK();
                
                if (!isset($childAtts['name'])) {
                    LSLogger::error('Attribute name not available in model parameter: ' . $child->getName() . '/' . $model . ' / ' . $module);
                    return false;
                } else {
                    $uniqueKey->name = trim($childAtts['name']);
                }
                
                if (!isset($childAtts['target'])) {
                    LSLogger::error('Attribute target not available in model parameter: ' . $child->getName() . '/' . $model . ' / ' . $module);
                    return false;
                } else {
                    $uniqueKey->target = trim($childAtts['target']);
                }
                
                $constraints[] = $uniqueKey;
            } else if ($child->getName() === 'foreignkey') {
                $foreignKey = new ModelFK();
                
                if (!isset($childAtts['name'])) {
                    LSLogger::error('Attribute name not available in model parameter: ' . $child->getName() . '/' . $model . ' / ' . $module);
                    return false;
                } else {
                    $foreignKey->name = trim($childAtts['name']);
                }
                
                if (!isset($childAtts['source'])) {
                    LSLogger::error('Attribute source not available in model parameter: ' . $child->getName() . '/' . $model . ' / ' . $module);
                    return false;
                } else {
                    $foreignKey->source = trim($childAtts['source']);
                }
                
                if (!isset($childAtts['target'])) {
                    LSLogger::error('Attribute target not available in model parameter: ' . $child->getName() . '/' . $model . ' / ' . $module);
                    return false;
                } else {
                    $foreignKey->target = trim($childAtts['target']);
                }
                
                if (isset($childAtts['ondelete'])) {
                    $foreignKey->onDelete = trim($childAtts['ondelete']);
                }
                
                if (isset($childAtts['onupdate'])) {
                    $foreignKey->onUpdate = trim($childAtts['onupdate']);
                }
                
                $constraints[] = $foreignKey;
            }
        }
        
        if (!$this->createModel($path, $model, $params)) {
            LSLogger::error('Could not generate model');
            return false;
        }
        
        if (!$this->createManager($path, $model, $params)) {
            LSLogger::error('Could not generate mmanager');
            return false;
        }
        
        if (!$this->createFactory($path, $model, $params)) {
            LSLogger::error('Could not generate factory');
            return false;
        }
        
        if (!$this->createCreateQuery($path, $model, $params, $constraints)) {
            
            LSLogger::error('Could not generate create query');
            return false;
        }
        
        if (!$this->createDropQuery($path, $model)) {
            LSLogger::error('Could not generate drop query');
            return false;
        }
        
        if (!$this->createAddConstraintsQuery($path, $model, $constraints)) {
            LSLogger::error('Could not generate add constraints query');
            return false;
        }
        
        if (!$this->createDropConstraintsQuery($path, $model, $constraints)) {
            LSLogger::error('Could not generate drop constraints query');
            return false;
        }
        
    }
    
    private function createModel($path, $model, $params) {
        $name = $model->name;
        $p = '';
        $constructor = '    public function __construct() {';
        $setFromArrayBody = '';
        $setFromAssocBody = '';
        $toArrayBody = '';
        $toAssocBody = '';
        $getSetMethods = '';
        $emptyMethods = '';
        $attributeMethodBody = '';
        $index = 0;
        
        foreach ($params as $param) {
            
            if (strlen($p) > 0) {
                $p .= PHP_EOL;
            }
            
            $constructor .= PHP_EOL;
            
            if (strlen($setFromArrayBody) > 0) {
                $setFromArrayBody .= PHP_EOL;
            }
            
            if (strlen($setFromAssocBody) > 0) {
                $setFromAssocBody .= PHP_EOL;
            }
            
            if (strlen($toArrayBody) > 0) {
                $toArrayBody .= ', ';
            }
            
            if (strlen($toAssocBody) > 0) {
                $toAssocBody .= PHP_EOL;
            }
            
            if (strlen($getSetMethods) > 0) {
                $getSetMethods .= PHP_EOL . PHP_EOL;
            }
            
            if (strlen($emptyMethods) > 0) {
                $emptyMethods .= PHP_EOL . PHP_EOL;
            }
            
            if (strlen($attributeMethodBody) > 0) {
                $attributeMethodBody .= ' else ';
            } else {
                $attributeMethodBody = '        ';
            }
            
            $p .= '    private $' . lcfirst($param->name) . ';';
            $constructor .= '        $this->' . lcfirst($param->name) . ' = new \ls\model\LSAttribute();' . PHP_EOL;
            $constructor .= '        $this->' . lcfirst($param->name) . '->setType(\'' . $param->mappedType . '\');';
            $setFromArrayBody .= '        $this->' . lcfirst($param->name) . '->setValue(\ls\model\LSDataConverter::convert($this->' . lcfirst($param->name) . '->getType(), $data[' . $index . ']));';
            $setFromAssocBody .= '        $this->' . lcfirst($param->name) . '->setValue(\ls\model\LSDataConverter::convert($this->' . lcfirst($param->name) . '->getType(), $data[\'' . $param->name . '\']));';
            $toArrayBody .= '$this->' . lcfirst($param->name) . '->getValue()';
            $toAssocBody .= '        $assocData[\'' . $param->name . '\'] = $this->' . lcfirst($param->name) . '->getValue();';
            $getSetMethods .= '    public function set' . ucfirst($param->name) . '($' . lcfirst($param->name) . ') {' . PHP_EOL . '        $this->' . lcfirst($param->name) . '->setValue($' . lcfirst($param->name) . ');' . PHP_EOL . '    }' . PHP_EOL . PHP_EOL;
            $getSetMethods .= '    public function get' . ucfirst($param->name) . '() {' . PHP_EOL . '        return $this->' . lcfirst($param->name) . '->getValue();' . PHP_EOL . '    }';
            $emptyMethods .= '    public function empty' . ucfirst($param->name) . '() {' . PHP_EOL . '        $this->' . lcfirst($param->name) . '->setEmpty(true);' . PHP_EOL . '    }' . PHP_EOL . PHP_EOL;
            $emptyMethods .= '    public function isEmpty' . ucfirst($param->name) . '() {' . PHP_EOL . '        return $this->' . lcfirst($param->name) . '->isEmpty();' . PHP_EOL . '    }';
            $attributeMethodBody .= 'if ($name === \'' . $param->name . '\') {' . PHP_EOL . '            return $this->' . lcfirst($param->name) . ';' . PHP_EOL . '        }';
            
            $index++;
        }
        
        $constructor .= PHP_EOL . '    }';        
        $content = $this->loadResourceModel($name, $p, $constructor, $setFromArrayBody, $setFromAssocBody, $toArrayBody, $toAssocBody, $getSetMethods, $emptyMethods, $attributeMethodBody);
        $handle = fopen($path . $name . '.php', 'w');
        
        if (!$handle) {
            LSLogger::error('Could not open file: ' . $path . $name . '.php');
            return false;
        }
        
        fwrite($handle, $content);
        fclose($handle);
        
        return true;
    }
    
    private function createManager($path, $model, $params) {
        $name = $model->name;
        $table = $model->table;
        $columns = null;
        $publicKeyName = null;
        
        foreach ($params as $param) {
            if ($columns !== null) {
                $columns .= ', ';
            }
            
            if ($param->publicKey === true) {
                $publicKeyName = lcfirst($param->name);
            }
            
            $columns .= '\'' . $param->name . '\'';
        }
        
        $customCode = '';
        
        if (file_exists($path . '/managers/' . $name . 'Mgr.php')) {
            $customCode = file_get_contents($path . '/managers/' . $name . 'Mgr.php');
            
            if ($customCode === false) {
                $customCode = '';
            } else {
                $parts = explode('//{CustomCodeBegin}', $customCode);
                
                if (count($parts) > 1) {
                    $customCode = $parts[1];
                }
                
                $parts = explode('//{CustomCodeEnd}', $customCode);
                
                if (count($parts) > 1) {
                    $customCode = $parts[0];
                }
                
                $customCode = trim($customCode);
            }
        }
        
        $content = $this->loadResourceModelMgr($name, $table, $columns, $publicKeyName, $customCode);
        $handle = fopen($path . '/managers/' . $name . 'Mgr.php', 'w');
        
        if (!$handle) {
            LSLogger::error('Could not open file: ' . $path . '/managers/' . $name . 'Mgr.php');
            return false;
        }
        
        fwrite($handle, $content);
        fclose($handle);
        
        return true;
    }
    
    private function createFactory($path, $model, $params) {
        $name = $model->name;
        $content = $this->loadResourceModelFactory($name);
        $handle = fopen($path . '/factories/' . $name . 'Factory.php', 'w');
        
        if (!$handle) {
            LSLogger::error('Could not open file: ' . $path . '/factories/' . $name . 'Factory.php');
            return false;
        }
        
        fwrite($handle, $content);
        fclose($handle);
        
        return true;
    }
    
    private function createCreateQuery($path, $model, $params, $constraints) {        
        $query = 'CREATE TABLE IF NOT EXISTS ' . $model->table . ' (' . PHP_EOL;
        $query = '';
        
        foreach ($params as $param) {
            if (strlen($query) > 0) {
                $query .= ',' . PHP_EOL;
            }
            
            $query .= '    ' . $param->name . ' ' . strtoupper($param->type);
            
            if ($param->notNull === true) {
                $query .= ' NOT NULL';
            }
            
            if ($param->publicKey === true) {
                $query .= ' PRIMARY KEY';
            }
            
            if ($param->uniqueKey === true) {
                $query .= ' UNIQUE KEY';
            }
            
            if ($param->autoIncrement === true) {
                $query .= ' AUTO_INCREMENT';
            }
            
            if ($param->defaultValue !== null) {
                $query .= ' DEFAULT ' . $param->defaultValue;
            }
            
        }
        
        foreach ($constraints as $constraint) {
            if ($constraint instanceof ModelUK) {
                $query .= ',' . PHP_EOL . '    UNIQUE KEY ' . $constraint->name . ' (' . $constraint->target . ')';
            }
        }
        
        $query = 'CREATE TABLE IF NOT EXISTS ' . $model->table . '(' . PHP_EOL . $query . PHP_EOL . ')';
        
        if ($model->engine !== null) {
            $query .= ' ENGINE = ' . $model->engine;
        }
        
        if ($model->charset !== null) {
            $query .= ' DEFAULT CHARSET = ' . $model->charset;
        }
        
        $handle = fopen($path . $model->name . '_CreateTable.query', 'w');
        
        if (!$handle) {
            LSLogger::error('Could not open file: ' . $path . $model->name . '_CreateTable.query');
            return false;
        }
        
        fwrite($handle, $query);
        fclose($handle);
        
        return true;
    }
    
    private function createDropQuery($path, $model) {        
        $query = 'DROP TABLE ' . $model->table;
        $handle = fopen($path . $model->name . '_DropTable.query', 'w');
        
        if (!$handle) {
            LSLogger::error('Could not open file: ' . $path . $model->name . '_DropTable.query');
            return false;
        }
        
        fwrite($handle, $query);
        fclose($handle);
        
        return true;
    }
    
    private function createAddConstraintsQuery($path, $model, $constraints) {        
        $query = '';
        
        foreach ($constraints as $constraint) {
            if ($constraint instanceof ModelFK) {
                if (strlen($query) > 0) {
                    $query .= PHP_EOL;
                }
                
                $query .= 'ALTER TABLE ' . $model->table . PHP_EOL;
                $query .= 'ADD CONSTRAINT ' . $constraint->name . ' FOREIGN KEY (' . $constraint->source . ') REFERENCES ' . $constraint->target;
                
                if ($constraint->onDelete !== null) {
                    $query .= PHP_EOL . 'ON DELETE ' . strtoupper($constraint->onDelete);
                }
                
                if ($constraint->onUpdate !== null) {
                    $query .= PHP_EOL . 'ON UPDATE ' . strtoupper($constraint->onUpdate);
                }
                
                $query .= ';';
            }
        }
        
        if (strlen($query) === 0) {
            return true;
        }
        
        $handle = fopen($path . $model->name . '_AddConstraints.query', 'w');
        
        if (!$handle) {
            LSLogger::error('Could not open file: ' . $path . $model->name . '_AddConstraints.query');
            return false;
        }
        
        fwrite($handle, $query);
        fclose($handle);
        
        return true;
    }
    
    private function createDropConstraintsQuery($path, $model, $constraints) {        
        $query = '';
        
        foreach ($constraints as $constraint) {
            if ($constraint instanceof ModelFK) {
                if (strlen($query) > 0) {
                    $query .= PHP_EOL;
                }
                
                $query .= 'ALTER TABLE ' . $model->table . PHP_EOL;
                $query .= 'DROP FOREIGN KEY ' . $constraint->name . ';';
            }
        }
        
        if (strlen($query) === 0) {
            return true;
        }
        
        $handle = fopen($path . $model->name . '_DropConstraints.query', 'w');
        
        if (!$handle) {
            LSLogger::error('Could not open file: ' . $path . $model->name . '_DropConstraints.query');
            return false;
        }
        
        fwrite($handle, $query);
        fclose($handle);
        
        return true;
    }
    
    private function loadResourceModel($name, $params, $constructor, $setFromArrayBody, $setFromAssocBody, $toArrayBody, $toAssocBody, $getSetMethods, $emptyMethods, $attributeMethodBody) {
        $path = INCLUDE_DIR . 'apps/lsmodeladmin/resources/Model.template';
        $content = file_get_contents($path);
        $content = str_replace('<name>', $name, $content);
        $content = str_replace('<params>', $params, $content);
        $content = str_replace('<constructor>', $constructor, $content);
        $content = str_replace('<setfromarraybody>', $setFromArrayBody, $content);
        $content = str_replace('<setfromassocbody>', $setFromAssocBody, $content);
        $content = str_replace('<toarraybody>', $toArrayBody, $content);
        $content = str_replace('<toassocbody>', $toAssocBody, $content);
        $content = str_replace('<getsetmethods>', $getSetMethods, $content);
        $content = str_replace('<emptymethods>', $emptyMethods, $content);
        $content = str_replace('<attributemethodbody>', $attributeMethodBody, $content);
        
        return $content;
    }
    
    private function loadResourceModelMgr($name, $table, $columns, $publicKeyName, $customCode) {
        $path = INCLUDE_DIR . 'apps/lsmodeladmin/resources/ModelMgr.template';
        $content = file_get_contents($path);
        $content = str_replace('<name>', $name, $content);
        $content = str_replace('<table>', $table, $content);
        $content = str_replace('<columns>', $columns, $content);
        $content = str_replace('<publickeyname>', $publicKeyName, $content);
        $content = str_replace('<customcode>', $customCode, $content);
        
        return $content;
    }
    
    public function loadResourceModelFactory($name) {
        $path = INCLUDE_DIR . 'apps/lsmodeladmin/resources/ModelFactory.template';
        $content = file_get_contents($path);
        $content = str_replace('<name>', $name, $content);
        
        return $content;
    }
    
    public function listModules() {
        $modulesPath = INCLUDE_DIR . 'modules/';
        $handle = opendir($modulesPath);
        $modules = array();
        
        if ($handle !== false) {
            while (($module = readdir($handle)) !== false) {
                if ($module !== '.' && $module !== '..' && !LSStringUtils::startsWith($module, '.')) {
                    $modules[] = $module;
                }
            }
        }
        
        return $modules;
    }
    
    public function listModuleModels($module) {
        $path = INCLUDE_DIR . '/modules/' . $module . '/models/';
        $handle = opendir($path);
        $models = array();
        
        if ($handle !== false) {
            while (($model = readdir($handle)) !== false) {
                if (LSStringUtils::endsWith($model, '.model') === true) {
                    $model = str_replace('.model', '', $model);
                    $models[] = $model;
                }
            }
        }

        closedir($handle);
        
        return $models;
    }
    
    public function generateModuleModels($module) {
        
        $models = $this->listModuleModels($module);
        
        foreach ($models as $model) {
            $this->generateModel($model, $module);
        }
    }
    
    public function generateModels() {
        
        $modules = $this->listModules();
        
        foreach ($modules as $module) {
            $models = $this->listModuleModels($module);
            
            foreach ($models as $model) {
                $this->generateModel($model, $module);
            }
        }
    }
    
    public function cleanModel($model, $module) {
        $path = INCLUDE_DIR . 'modules/' . $module . '/models/' . $model;
        
        if (file_exists($path . '.php')) {
            unlink($path . '.php');
        }
        
        if (file_exists($path . '_CreateTable.query')) {
            unlink($path . '_CreateTable.query');
        }
        
        if (file_exists($path . '_DropTable.query')) {
            unlink($path . '_DropTable.query');
        }
        
        if (file_exists($path . '_AddConstraints.query')) {
            unlink($path . '_AddConstraints.query');
        }
        
        if (file_exists($path . '_DropConstraints.query')) {
            unlink($path . '_DropConstraints.query');
        }
    }
    
    public function cleanModuleModels($module) {
        
        $models = $this->listModuleModels($module);
        
        foreach ($models as $model) {
            $this->cleanModel($model, $module);
        }
    }
    
    public function cleanModels() {
        
        $modules = $this->listModules();
        
        foreach ($modules as $module) {
            $models = $this->listModuleModels($module);
            
            foreach ($models as $model) {
                $this->cleanModel($model, $module);
            }
        }
    }
    
    public function createModelTable($model, $module) {
        $queryPath = INCLUDE_DIR . 'modules/' . $module . '/models/' . $model . '_CreateTable.query';
        $conn = dbConnect(cget('DBHost'), cget('DBUserName'), cget('DBPassword'));
        
        if (file_exists($queryPath)) {
            $result = dbFileQuery($conn, $queryPath);
            
            if (!$result) {
                return dbError($conn);
            }
        }
        
        return true;
    }
    
    public function createModuleModelsTables($module) {
        
        $models = $this->listModuleModels($module);
        
        foreach ($models as $model) {
            $this->createModelTable($model, $module);
        }
    }
    
    public function createModelsTables() {
        
        $modules = $this->listModules();
        
        foreach ($modules as $module) {
            $models = $this->listModuleModels($module);
            
            foreach ($models as $model) {
                $this->createModelTable($model, $module);
            }
        }
    }
    
    public function dropModelTable($model, $module) {
        $queryPath = INCLUDE_DIR . 'modules/' . $module . '/models/' . $model . '_DropTable.query';
        $conn = dbConnect(cget('DBHost'), cget('DBUserName'), cget('DBPassword'));
        
        if (file_exists($queryPath)) {
            $result = dbFileQuery($conn, $queryPath);
            
            if (!$result) {
                return dbError($conn);
            }
        }
        
        return true;
    }
    
    public function dropModuleModelsTables($module) {
        
        $models = $this->listModuleModels($module);
        
        foreach ($models as $model) {
            $this->dropModelTable($model, $module);
        }
    }
    
    public function dropModelsTables() {
        
        $modules = $this->listModules();
        
        foreach ($modules as $module) {
            $models = $this->listModuleModels($module);
            
            foreach ($models as $model) {
                $this->dropModelTable($model, $module);
            }
        }
    }    
    
    public function createModelTableConstraints($model, $module) {
        $queryPath = INCLUDE_DIR . 'modules/' . $module . '/models/' . $model . '_AddConstraints.query';
        $conn = dbConnect(cget('DBHost'), cget('DBUserName'), cget('DBPassword'));
        
        if (file_exists($queryPath)) {
            $result = dbFileQueries($conn, $queryPath);
            
            if (!$result) {
                return dbError($conn);
            }
        }
        
        return true;
    }
    
    public function createModuleModelsTablesConstraints($module) {
        
        $models = $this->listModuleModels($module);
        
        foreach ($models as $model) {
            $this->createModelTableConstraints($model, $module);
        }
    }
    
    public function createModelsTablesConstraints() {
        
        $modules = $this->listModules();
        
        foreach ($modules as $module) {
            $models = $this->listModuleModels($module);
            
            foreach ($models as $model) {
                $this->createModelTableConstraints($model, $module);
            }
        }
    }
    
    public function dropModelTableConstraints($model, $module) {
        $queryPath = INCLUDE_DIR . 'modules/' . $module . '/models/' . $model . '_DropConstraints.query';
        $conn = dbConnect(cget('DBHost'), cget('DBUserName'), cget('DBPassword'));
        
        if (file_exists($queryPath)) {
            $result = dbFileQueries($conn, $queryPath);
            
            if (!$result) {
                return dbError($conn);
            }
        }
        
        return true;
    }
    
    public function dropModuleModelsTablesConstraints($module) {
        
        $models = $this->listModuleModels($module);
        
        foreach ($models as $model) {
            $this->dropModelTableConstraints($model, $module);
        }
    }
    
    public function dropModelsTablesConstraints() {
        
        $modules = $this->listModules();
        
        foreach ($modules as $module) {
            $models = $this->listModuleModels($module);
            
            foreach ($models as $model) {
                $this->dropModelTableConstraints($model, $module);
            }
        }
    }
    
}
