<?php

namespace ls\internal;

abstract class Servlet extends Resource {
    
    /**
     *
     * @var type 
     */
    private $attachedOperations = array();
    
    /**
     * 
     * @param type $method
     * @param type $args
     * @return type
     */
    public function __call($method, $args) {
        if (!isset($this->$method) && isset($this->attachedOperations[$method])) {
            $operation = $this->attachedOperations[$method];
            $scriptletArgs = array();
            $i = 0;
            
            foreach ($operation->getArgs() as $argKey => $argValue) {
                $this->setOperationArgument($argKey, $argValue, $args, $i, $scriptletArgs);
                $i++;
            }
            
            return $this->scriptlet($operation->getScriptletName(), $scriptletArgs);
        }
        
        return parent::__call($method, $args);
    }
    
    /**
     * 
     */
    public abstract function init();
    
    /**
     * 
     * @param type $name
     * @throws ResourceNotFoundException
     */
    public function useLib($name) {
        $type = 'lib';
        $resourceInfo = $this->getApplication()->locateResource($name, $type);
        
        if ($resourceInfo !== false) {
            require_once $resourceInfo->getPath();
        } else {
            throw new ResourceNotFoundException(ucfirst($type) . ' ' . $name . ' not found');
        }
    }
    
    /**
     * 
     * @param type $name
     * @param array $args
     * @return type
     * @throws ResourceNotFoundException
     */
    public function scriptlet($name, $args = null) {
        $type = 'scriptlet';
        $resourceInfo = $this->getApplication()->locateResource($name, $type);
        
        if ($resourceInfo !== false) {
            if ($args === null) {
                $args = array();
            }
            
            extract($args);
            return (require $resourceInfo->getPath());
        } else {
            throw new ResourceNotFoundException(ucfirst($type) . ' ' . $name . ' not found');
        }
    }
    
    /**
     * 
     * @param type $name
     * @param type $args
     * @throws ResourceNotFoundException
     */
    public function page($name, $args = null) {
        $resourceInfo = $this->getApplication()->locateResource($name, 'page', '.php');
        
        if ($resourceInfo !== false) {
            $page = new Page($resourceInfo->getName(), $resourceInfo->getContainer(), $args);
            $page->loadPageFile();
            exit;
        } else {
            throw new ResourceNotFoundException('Page ' . $name . ' not found');
        }
    }
    
    /**
     * 
     * @param type $name
     * @param type $scriptletName
     * @param type $args
     */
    public function attachOperation($name, $scriptletName, $args = null) {
        $operation = new Operation($name, $scriptletName, $args);
        $this->attachedOperations[$name] = $operation;
    }
    
    /**
     * 
     */
    public function loadAutoIncludeFile() {
        $path = $this->getApplication()->getAutoIncludeFilePath();
        
        if ($path !== false) {
            require $path;
        }
    }
    
    /**
     * 
     * @param string $argKey
     * @param string $argValue
     * @param array $args
     * @param int $i
     * @param array $scriptletArgs
     * @throws \BadMethodCallException
     */
    private function setOperationArgument($argKey, $argValue, $args, $i, &$scriptletArgs) {
        $key = null;
        $value = null;

        if (is_int($argKey)) {
            $key = $argValue;

            if (array_key_exists($i, $args)) {
                $value = $args[$i];
            } else {
                throw new \BadMethodCallException();
            }
        } else {
            $key = $argKey;

            if (array_key_exists($i, $args)) {
                $value = $args[$i];
            } else {
                $value = $argValue;
            }
        }

        $scriptletArgs[$key] = $value;
    }
    
}
