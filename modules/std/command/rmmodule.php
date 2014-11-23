<?php

class rmmodule extends \ls\internal\Command {
    
    public function printHelp() {
        $this->page('RmModuleHelp');
    }

    public function execute($name) {
        $ls = new ls\internal\LS();
        $ls->loadConfigurationFile();
        $application = new \ls\internal\Application($name, $ls);
        $module = new \ls\internal\Module($name, $application);
        
        if ($module->isCreated()) {
            if ($module->delete()) {
                $this->page('Message', array('message' => 'Successfully deleting module ' . $name));
            } else {
                $this->page('Message', array('message' => 'Could not delete module ' . $name));
            }
        } else {
            $this->page('Message', array('message' => 'Module ' . $name . ' not found.'));
        }
    }
    
}
