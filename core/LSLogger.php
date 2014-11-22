<?php

namespace ls\core;

class LSLogger {
    
    private static $LOG_INFO_ENABLED = true;
    
    private static $LOG_DEBUG_ENABLED = true;
    
    private static $LOG_WARN_ENABLED = true;
    
    private static $LOG_ERROR_ENABLED = true;
    
    private static $LOG_INFO_SIZE = 1000000;
    
    private static $LOG_DEBUG_SIZE = 1000000;
    
    private static $LOG_WARN_SIZE = 1000000;
    
    private static $LOG_ERROR_SIZE = 1000000;
    
    public static function info($message) {
        
        if (LSLogger::$LOG_INFO_ENABLED === false || !whas('App:Name')) {
            return;
        }
        
        $appName = wget('App:Name');
        $path = includeDir() . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR . $appName . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'info';
        LSLogger::rollLog($path, LSLogger::$LOG_INFO_SIZE);
        $handle = fopen($path . '.log', 'a');
        
        if (!$handle) {
            return;
        }
        
        fwrite($handle, '[' . date('d/m/y H:i:s') . '] ' . $message . PHP_EOL);
        fclose($handle);
    }
    
    public static function debug($message) {
        
        if (LSLogger::$LOG_DEBUG_ENABLED === false || !whas('App:Name')) {
            return;
        }
        
        $appName = wget('App:Name');
        $path = includeDir() . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR . $appName . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'debug';
        LSLogger::rollLog($path, LSLogger::$LOG_DEBUG_SIZE);
        $handle = fopen($path . '.log', 'a');
        
        if (!$handle) {
            return;
        }
        
        fwrite($handle, '[' . date('d/m/y H:i:s') . '] ' . $message . PHP_EOL);
        fclose($handle);
    }
    
    public static function warn($message) {
        
        if (LSLogger::$LOG_WARN_ENABLED === false || !whas('App:Name')) {
            return;
        }
        
        $appName = wget('App:Name');
        $path = includeDir() . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR . $appName . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'debug';
        LSLogger::rollLog($path, LSLogger::$LOG_WARN_SIZE);
        $handle = fopen($path . '.log', 'a');
        
        if (!$handle) {
            return;
        }
        
        fwrite($handle, '[' . date('d/m/y H:i:s') . '] ' . $message . PHP_EOL);
        fclose($handle);
        
    }
    
    public static function error($message) {
        
        if (LSLogger::$LOG_ERROR_ENABLED === false || !whas('App:Name')) {
            return;
        }
        
        $appName = wget('App:Name');
        $path = includeDir() . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR . $appName . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'error';
        LSLogger::rollLog($path, LSLogger::$LOG_ERROR_SIZE);
        $handle = fopen($path . '.log', 'a');
        
        if (!$handle) {
            return;
        }
        
        fwrite($handle, '[' . date('d/m/y H:i:s') . '] ' . $message . PHP_EOL);
        fclose($handle);
    }
    
    private static function rollLog($path, $maxSize) {
        
        if (file_exists($path . '.log')) {
            $size = filesize($path . '.log');
            
            if ($size !== false && $size > $maxSize) {
                rename($path . '.log', $path . '_' . time() . '.log');
            }
        }
        
    }
    
}
