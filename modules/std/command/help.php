<?php

class help extends \ls\internal\Command {
    
    public function printHelp() {
        $this->page('HelpHelp');
    }
    
    public function execute($command = null) {
        if ($command !== null) {
            $args = explode(':', $command);
            
            if (count($args) === 2) {
                $ls = new ls\internal\LS();
                $ls->loadConfigurationFile();
                $application = new \ls\internal\Application('CommandRunner', $ls);
                $application->addModule($args[0]);
                $resourceInfo = $application->locateResource($args[1], 'command');
                $this->runCommand($resourceInfo);
            }
            
            $this->page('Message', array('message' => 'Command ' . $command . ' no found.'));
        } else {
            $this->page('GlobalHelp');
        }
    }
    
    private function runCommand($resourceInfo) {
        if ($resourceInfo !== false) {
            require_once $resourceInfo->getPath();
            $commandClass = new \ReflectionClass($resourceInfo->getName());
            $cmd = $commandClass->newInstance($resourceInfo->getName(), $resourceInfo->getContainer());
            $cmd->printHelp();
            exit;
        }
    }

}
