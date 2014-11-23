<?php

class help extends \ls\internal\Command {
    
    public function printHelp() {
        
    }
    
    public function execute($command = null) {
        if ($command !== null) {
            $resourceInfo = $this->getApplication()->locateResource($command, 'command');
            
            if ($resourceInfo !== false) {
                require_once $resourceInfo->getPath();
                $commandClass = new \ReflectionClass($resourceInfo->getName());
                $cmd = $commandClass->newInstance($resourceInfo->getName(), $resourceInfo->getContainer());
                $cmd->printHelp();
            } else {
                $this->page('CommandNotFound', array('command' => $command));
            }
        } else {
            $this->page('GlobalHelp');
        }
    }

}
