<?php

class info extends \ls\internal\Command {
    
    public function printHelp() {
        $this->page('InfoHelp');
    }
    
    public function execute($element = null) {
        $path = BASE_DIR . 'leosphp.xml';
        
        if (file_exists($path)) {
            $node = simplexml_load_file($path);
            $infoElements = null;
            
            if ($element === null) {
                $infoElements = array('name', 'description', 'version');
            } else {
                $infoElements = array($element);
            }
            
            $args = array();
            
            foreach ($node->children() as $child) {
                $index = array_search($child->getName(), $infoElements);
                
                if ($index !== false) {
                    $args[$child->getName()] = dom_import_simplexml($child)->textContent;
                }
            }
            
            $this->page('Info', $args);
        }
    }

}
