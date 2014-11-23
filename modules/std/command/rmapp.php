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
                $this->page('DeletingApplicationSuccessful', array('name' => $name));
            } else {
                $this->page('DeletingApplicationError', array('name' => $name));
            }
        } else {
            $this->page('ApplicationNotFound', array('name' => $name));
        }
    }
    
}
