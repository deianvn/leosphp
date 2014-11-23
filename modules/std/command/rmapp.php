<?php

class rmapp extends \ls\internal\Command {
    
    public function printHelp() {
        $this->page('RmAppHelp');
    }

    public function execute($name) {
        $ls = new ls\internal\LS();
        $ls->loadConfigurationFile();
        $application = new \ls\internal\Application($name, $ls);
        
        if ($application->isCreated()) {
            if ($application->delete()) {
                $this->page('Message', array('message' => 'Successfully deleting application ' . $name));
            } else {
                $this->page('Message', array('message' => 'Could not delete application ' . $name));
            }
        } else {
            $this->page('Message', array('message' => 'Application ' . $name . ' not found.'));
        }
    }
    
}
