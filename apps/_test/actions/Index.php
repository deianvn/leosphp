<?php

class Index extends LSAction {
    
    public function init() {
        useModel('Object');
        useModel('Attribute');
    }
    
    public function execute() {
        //Save object to database
        $o1 = new Object();
        $o1->setName('TestObject1');
        $o1->save();
        //Retrieve object from database
        $o2 = ObjectMgr::instance()->get('first')->condition('Name =', 'TestObject1')->submit();
        
        if ($o2 !== false) {
            wput('Object', $o2);
        } else {
            wput('Error', true);
        }
        
        $this->page('Page');
    }
    
}
