<?php

class mkapp extends \ls\internal\Command {
    
    public function printHelp() {
        $this->page('MkAppHelp');
    }
    
    public function execute($name, $additionalResources = null) {
        $ls = new ls\internal\LS();
        $ls->loadConfigurationFile();
        $application = new \ls\internal\Application($name, $ls);
        
        if ($additionalResources === null) {
            $additionalResources = array();
        } else {
            $additionalResources = explode(',', $additionalResources);
        }
        
        if (!$application->isCreated()) {
            if ($application->create(true, $additionalResources) === true) {
                $this->page('CreatingApplicationSuccessful', array('name' => $name));
            }
        } else {
            $this->page('CreatingApplicationError', array('name' => $name));
        }
    }

}
