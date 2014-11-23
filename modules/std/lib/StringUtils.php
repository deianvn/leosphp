<?php

namespace ls\std;

class StringUtils {
    
    public static function startsWith($haystack, $needle) {
        return !strncmp($haystack, $needle, strlen($needle));
    }
    
    public static function endsWith($haystack, $needle) {
        $length = strlen($needle);
        
        if ($length === 0) {
            return true;
        }
        
        return substr($haystack, -$length) === $needle;
    }

}

