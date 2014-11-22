<?php

class LSDateUtils {
    
    public static function toUnixTimestamp($datetime) {
//        list($date, $time) = explode(' ', $timestamp);
//        list($year, $month, $day) = explode('-', $date);
//        list($hour, $minute, $second) = explode(':', $time);
//        $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
//
//        return $timestamp;
        
        return strtotime($datetime);
    }
    
    public static function toSQLDateTime($timestamp) {
        return date('Y-m-d H:i:s', $timestamp); 
    }
    
}
