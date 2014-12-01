<?php

class LSConfig {
    
    /**
     *
     * @var string 
     */
    private $localeParameterName = 'Locale';
    
    /**
     *
     * @var string 
     */
    private $defaultApplicationName;
    
    public function getLocaleParameterName() {
        return $this->localeParameterName;
    }

    public function getDefaultApplicationName() {
        return $this->defaultApplicationName;
    }

    public function setLocaleParameterName($localeParameterName) {
        $this->localeParameterName = $localeParameterName;
    }

    public function setDefaultApplicationName($defaultApplicationName) {
        $this->defaultApplicationName = $defaultApplicationName;
    }
    
}
