<?php

namespace ls\internal;

class LS {
    
    /**
     *
     * @var string 
     */
    private $localeParameterName = 'Locale';
    
    /**
     * 
     * @return string
     */
    public function getLocaleParameterName() {
        return $this->localeParameterName;
    }
    
    /**
     * 
     * @param string $localeParameterName
     */
    public function setLocaleParameterName($localeParameterName) {
        $this->localeParameterName = $localeParameterName;
    }
    
    /**
     * 
     * @return string
     */
    public function getPath() {
        return BASE_DIR;
    }
    
    /**
     * 
     * @return boolean
     */
    public function hasConfigurationFile() {
        return file_exists($this->makeConfigurationFilePath());
    }
    
    /**
     * 
     */
    public function loadConfigurationFile() {
        if ($this->hasConfigurationFile()) {
            require $this->makeConfigurationFilePath();
        }
    }
    
    /**
     * 
     * @param string $name
     * @return boolean
     */
    public function hasApplication($name) {
        $application = new Application($name);
        return $application->isCreated();
    }
    
    /**
     * 
     * @return boolean
     */
    private function makeConfigurationFilePath() {
        return $this->getPath() . 'config.php';
    }
    
}
