<?php

namespace ls\core;

class LSConverter {

    private static $instance;

    public static function instance() {
        if (!isset(self::$instance)) {
            self::$instance = new LSConverter();
        }

        return self::$instance;
    }

    public function convert($type, $value) {
        if (is_array($value)) {
            for ($i = 0; $i < count($value); $i++) {
                $value[$i] = $this->convertValue($type, $value[$i]);
            }
            
            return $value;
        } else {
            return $this->convertValue($type, $value);
        }
    }
    
    private function convertValue($type, $value) {
        if ($type === 'string') {
            return $value;
        } else if ($type === 'int') {
            return intval($value);
        } else if ($type === 'float') {
            return floatval($value);
        } else if ($type === 'boolean') {
            return $value === 'true' || boolval($value);
        } else {
            return $value;
        }
    }

}
