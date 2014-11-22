<?php

namespace ls\core;

class LSCli {
    
    private $argv;
    
    public function __construct($argv) {
        $this->argv = $argv;
    }
    
    public function run() {
        if (count($this->argv) < 2) {
            $this->printHelp();
            return;
        } else {
            $targets = explode(':', $this->argv[1]);
            
            if ((count($targets) != 2 && count($targets) != 3) || strlen($targets[0] === 0 || strlen($targets[1] === 0))) {
                $this->printHelp();
                return;
            }
            
            $params = count($this->argv) >= 3 ? array_slice($this->argv, 2) : null;
            $this->prepareApplication($targets[0]);
            $this->prepareCommand($targets[1], $params);
            $this->runCommand();
        }
        
    }
    
    public function prepareApplication($module = null) {
        $app = new LSApplication('lscli');
        
        if ($module !== null) {
            $app->addModule($module);
        }
        
        wput('App:Name', 'lscli');
        wput('Application', $app);
    }
    
    private function prepareCommand($name, $params = null) {
        
    }
    
    private function runCommand() {
        
    }
    
    private function printHelp() {
        $this->prepareApplication();
        $this->prepareCommand('PrintCliHelp');
        $this->runCommand();
    }
    
}
