<?php

namespace ls\model;

class LSDataConverter {
    
    public static function convert($type, $value) {
        if ($value === null) {
            return null;
        }
        
        if ($type === 'string') {
            return $value;
        } else if ($type === 'int') {
            return intval($value);
        } else if ($type === 'float') {
            return floatval($value);
        } else if ($type === 'double') {
            return doubleval($value);
        } else if ($type === 'boolean') {
            return $value != 0 && $value != 'false';
        } else if ($type === 'unixtimestamp') {
            return $value; //todo
        }
    }
    
    public static function convertBack($type, $value) {
        if ($value === null) {
            return null;
        }
        
        if ($type === 'unixtimestamp') {
            return $value;
        } else if ($type === 'boolean') {
            if ($value === true) {
                return 1;
            } else {
                return 0;
            }
        }
        
        return $value;
    }
    
}
