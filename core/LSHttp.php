<?php

namespace ls\core;

class LSHttp {
    public static function send404() {
        header('HTTP/1.0 404 Not Found');
        exit(0);
    }
}
