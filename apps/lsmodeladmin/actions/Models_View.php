<?php

class Models_View extends LSAction {
    public function init() {}
    
    public function execute() {
        $this->scriptlet('LoadModels');
        $this->page('ModelsPage');
    }
    
}

?>