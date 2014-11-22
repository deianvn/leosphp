<?php

class Hello extends ls\internal\Command {
    
    public function printHelp() {
        $this->page('HelloHelp');
    }
    
    public function execute($msg) {
        echo $msg . "\n";
        $this->page('Hello');
    }

}
