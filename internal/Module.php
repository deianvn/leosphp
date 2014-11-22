<?php

namespace ls\internal;

class Module extends ResourceContainer {
    
    /**
     *
     * @var ls\internal\Application
     */
    private $application;
    
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
        $this->setApplication($application);
        parent::__construct($name, $application->getLs());
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
     * @return ls\internal\Application
     */
    public function getApplication() {
        return $this->application;
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
     * @param \ls\internal\Application $application
     * @throws InvalidArgumentException
     */
    private function setApplication($application) {
        if ($application instanceof Application) {
            $this->application = $application;
        } else {
            throw new \InvalidArgumentException('Valid application must be provided!');
        }
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
