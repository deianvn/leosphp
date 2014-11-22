<?php

namespace ls\core;

class LSLocMgr {

    public static function loadLocFile($path) {
        global $lsloc;
        
        if (file_exists($path)) {
            $locData = parse_ini_file($path);

            foreach ($locData as $key => $value) {
                $lsloc[$key] = $value;
            }
            
            return true;
        }
        
        return false;
    }

}
