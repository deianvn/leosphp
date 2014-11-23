<?php

class mkmodule extends \ls\internal\Command {
    
    public function printHelp() {
        $this->page('MkModuleHelp');
    }
    
    public function execute($name, $additionalResources = null) {
        $ls = new ls\internal\LS();
        $ls->loadConfigurationFile();
        $application = new \ls\internal\Application($name, $ls);
        $module = new \ls\internal\Module($name, $application);
        
        if ($additionalResources === null) {
            $additionalResources = array();
        } else {
            $additionalResources = explode(',', $additionalResources);
        }
        
        if (!$module->isCreated()) {
            if ($module->create(true, $additionalResources) === true) {
                $this->page('Message', array('message' => 'Successfully creating module ' . $name));
            }
        } else {
            $this->page('Message', array('message' => 'Could not create module ' . $name));
        }
    }

}
