<?php

namespace ls\internal;

class Module extends ResourceContainer {
    
    /**
     *
     * @var type 
     */
    private $config;
    
    /**
     * 
     * @param type $name
     * @param type $application
     * @param type $config
     */
    public function __construct($name, $application, $config = null) {
        parent::__construct($name, $application);
        $this->setConfig($config);
    }
    
    /**
     * 
     * @return string
     */
    public function getPath() {
        return BASE_DIR . 'modules/' . $this->getName() . '/';
    }
    
    /**
     * 
     * @param type $key
     * @return type
     */
    public function hasConfig($key) {
        return isset($this->config[$key]);
    }
    
    /**
     * 
     * @param type $key
     * @return type
     */
    public function getConfig($key) {
        return $this->config[$key];
    }
    
    /**
     * 
     * @param type $config
     * @throws InvalidArgumentException
     */
    private function setConfig($config) {
        if ($config !== null) {
            if (is_array($config)) {
                $this->config = $config;
            } else {
                throw new \InvalidArgumentException('Config parameter should be an array!');
            }
        }
    }
    
}
