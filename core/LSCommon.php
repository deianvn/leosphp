<?php

namespace ls\core;

abstract class LSCommon {

    protected function getContext() {
        return context();
    }
    
    protected function l($key) {
        return l($key);
    }
    
}
