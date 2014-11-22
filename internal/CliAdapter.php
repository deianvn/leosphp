<?php

namespace ls\internal;

class CliAdapter extends Adapter {
    
    private $module;
    
    private $commandName;
    
    private $locale;
    
    private $args;
    
    private $application;
    
    public function __construct($argv) {
        $this->parseArguments($argv);
        $this->setupApplication();
        $this->executeCommand();
    }
    
    public function run() {
        
    }
    
    private function parseArguments($argv) {
        $this->args = array();
        $i = 1;
        $size = count($argv);
        
        if ($i < $size) {
            $this->parseModuleAndCommand($argv[$i]);
            $i++;
        }
        
        while ($i < $size) {
            $this->addArgument($argv[$i++]);
        }
    }
    
    private function parseModuleAndCommand($param) {
        $params = explode(":", $param);
        $size = count($params);
        
        if ($this->setModule($params, $size) && $this->setCommandName($params, $size) && $this->setLocale($params, $size) === true) {
            return;
        }
        
        $this->printHelp();
    }
    
    private function setModule($params, $size) {
        if ($size >= 1 && strlen($params[0]) > 0) {
            $this->module = $params[0];
            return true;
        }
        
        return false;
    }
    
    private function setCommandName($params, $size) {
        if ($size >= 2 && strlen($params[1]) > 0) {
            $this->commandName = $params[1];
            return true;
        }
        
        return false;
    }
    
    private function setLocale($params, $size) {
        if ($size >= 3 && strlen($params[2]) > 0) {
            $this->locale = $params[2];
        }
        
        return true;
    }
    
    private function addArgument($param) {
        if (strlen($param) > 0) {
            $this->args[] = $param;
        }
    }
    
    private function setupApplication() {
        $ls = new LS();
        $ls->loadConfigurationFile();
        $this->application = new Application('CommandRunner', $ls);
        $this->application->addModule($this->module);
    }
    
    private function executeCommand() {
        $resourceInfo = $this->application->locateResource($this->commandName, 'command');
        
        if ($resourceInfo !== false) {
            if ($this->executeServlet($resourceInfo, $this->args) === true) {
                return;
            }
        }
        
        $this->printHelp();
    }
    
    private function printHelp() {
        echo "\nHelp\n\n";
        exit;
    }
    
}
