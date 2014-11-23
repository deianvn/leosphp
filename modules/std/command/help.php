<?php

class help extends \ls\internal\Command {
    
    public function printHelp() {
        
    }
    
    public function execute($command = null) {
        if ($command !== null) {
            $path = $this->getApplication()->locateResource($command, 'command');
            
            if ($path !== false) {
                
            } else {
                $this->page('CommandNotFound', array('command' => $command));
            }
        } else {
            
        }
    }

}
