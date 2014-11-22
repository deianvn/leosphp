<?php

namespace ls\core;

class LSRouter {
    
    public static function rewrite($url) {
        $urlSegments = explode('/', $url);
        $segmentsCount = count($urlSegments);
        
        foreach (wget('Routes:Rewrites') as $key => $value) {
            $segments = explode('/', $key);
            
            if (count($segments) === $segmentsCount) {
                $valid = true;
                
                for ($i = 0; $i < $segmentsCount; $i++) {
                    if (preg_match('/^' . $segments[$i] . '$/', $urlSegments[$i]) === 0) {
                        $valid = false;
                        break;
                    }
                }
                
                if ($valid === true) {
                    $search = array();

                    for ($i = 0; $i < $segmentsCount; $i++) {
                        $search[] = '$' . ($i + 1);
                    }

                    return str_replace($search, $urlSegments, $value);
                }
            }
        }
        
        return $url;
    }
    
    public static function mapApplication() {
        $application = wget('App:Name');
        
        foreach (wget('Routes:Mappings') as $key => $value) {
            if (strpos($key, '/') === false && $key === $application) {
                wput('App:Name', $value);
                return;
            }
        }
    }
    
    public static function mapAction() {
        $application = wget('App:Name');
        $action = wget('Request:ActionName');
        
        foreach (wget('Routes:Mappings') as $key => $value) {
            if (strpos($key, '/') !== false) {
                $segments = explode('/', $key);
                
                if (count($segments) === 2 && $segments[0] === $application && $segments[1] === $action) {
                    wput('Request:ActionName', $value);
                    return;
                }
            }
        }
    }
    
}
