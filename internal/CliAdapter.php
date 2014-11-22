<?php

namespace ls\internal;

class CliAdapter extends Adapter {
    
    private $module;
    
    private $commandName;
    
    private $command;
    
    private $commandClass;
    
    private $locale;
    
    private $args;
    
    private $application;
    
    public function __construct($argv) {
        $this->parseArguments($argv);
        $this->setupApplication();
        $this->prepareCommand();
        $this->execute();
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
    
    private function prepareCommand() {
        $resourceInfo = $this->application->locateResource($this->commandName, 'command');
        
        if ($resourceInfo !== false) {
            require $resourceInfo->getPath();
            $this->commandClass = new \ReflectionClass($resourceInfo->getName());
            $this->command = $this->commandClass->newInstance($resourceInfo->getName(), $resourceInfo->getContainer());
            return;
        }
        
        $this->printHelp();
    }
    
    private function execute() {
        $this->command->init();
        
        if ($this->commandClass->hasMethod('execute')) {
            $commandObjectExecuteMethod = $this->commandClass->getMethod('execute');
            $reqNumber = $commandObjectExecuteMethod->getNumberOfRequiredParameters();
            $allNumber = $commandObjectExecuteMethod->getNumberOfParameters();
            $number = count($this->args);
            
            if ($number >= $reqNumber && $number <= $allNumber) {
                $this->executeCommand($commandObjectExecuteMethod);
            } else {
                $this->printCommandHelp();
            }
        }
        
        $this->printHelp();
    }
    
    private function executeCommand($commandObjectExecuteMethod) {
        try {
            $commandObjectExecuteMethod->invokeArgs($this->command, $this->args);
        } catch (\InvocationException $e) {
            
        }
    }
    
    private function printCommandHelp() {
        echo "\n";
        $this->command->printHelp();
        echo "\n";
    }
    
    private function printHelp() {
        echo "\nHelp\n\n";
        exit;
    }
    
}
