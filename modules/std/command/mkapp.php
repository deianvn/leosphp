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
            $result = $application->create(true, $additionalResources);
            $application->addModule('std');
            $result = $result && $application->createAutoIncludeFile();
            $result = $result && $application->createConfigurationFile();
            
            if ($result === true) {
                $this->page('Message', array('message' => 'Successfully creating application ' . $name));
            }
        } else {
            $this->page('Message', array('message' => 'Could not create application ' . $name));
        }
    }

}
