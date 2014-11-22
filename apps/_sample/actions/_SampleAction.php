<?php

class _SampleAction extends LSAction {
    
    public function init() {}
    
    public function execute($message) {
        wput('Message', $message);
        $this->page('_SamplePage');
    }
    
}
