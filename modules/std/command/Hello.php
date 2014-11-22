<?php

class Hello extends ls\internal\Command {
    
    
    public function init() {
        
    }
    
    public function execute($msg) {
        echo $msg . "\n";
        $this->page('Hello');
    }

}
