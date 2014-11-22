<?php

namespace ls\core;

class LSWorkflowMgr {

    public static function getScriptletPath($name) {
        $path = LSWorkflowMgr::getWFResPath($name, 'scriptlets');

        if ($path !== false) {
            if (wget('Application')->isLocalizationEnabled() === true) {
                $localePath = LSWorkflowMgr::getLocalePath($name, 'scriptlets', true);

                if ($localePath !== false) {
                    LSLocMgr::loadLocFile($localePath);
                }
            }

            return $path . '.php';
        } else {
            LSLogger::error('Scriptlet [' . $name . '] not found');
            header("HTTP/1.0 404 Not Found");
            exit(0);
        }
    }

    public static function getSnippetPath($name) {
        $path = LSWorkflowMgr::getWFResPath($name, 'snippets', true);

        if ($path !== false) {
            if (wget('Application')->isLocalizationEnabled() === true) {
                $localePath = LSWorkflowMgr::getLocalePath($name, 'snippets', true);

                if ($localePath !== false) {
                    LSLocMgr::loadLocFile($localePath);
                }
            }

            return $path . '.php';
        } else {
            LSLogger::error('Snippet [' . $name . '] not found');
            header("HTTP/1.0 404 Not Found");
            exit(0);
        }
    }

    public static function getPagePath($name) {
        $path = LSWorkflowMgr::getWFResPath($name, 'pages', true);
        
        if ($path !== false) {
            if (wget('Application')->isLocalizationEnabled() === true) {
                $localePath = LSWorkflowMgr::getLocalePath($name, 'pages', true);
                
                if ($localePath !== false) {
                    LSLocMgr::loadLocFile($localePath);
                }
            }

            return $path . '.php';
        } else {
            LSLogger::error('Page [' . $name . '] not found');
            header("HTTP/1.0 404 Not Found");
            exit(0);
        }
        
        return null;
    }
    
    public static function getCommandPath($name) {
        $path = LSWorkflowMgr::getWFResPath($name, 'commands', true);
        
        if ($path !== false) {
            return $path . '.php';
        } else {
            echo 'Command [' . $name . '] not found\n';
            exit(0);
        }
        
        return null;
    }

    private static function getWFResPath($resource, $resourceType, $useThemes = false) {
        $appName = wget('App:Name');
        $path = includeDir() . 'apps' . DIRECTORY_SEPARATOR . $appName . DIRECTORY_SEPARATOR . $resourceType . DIRECTORY_SEPARATOR . $resource;
        
        if (file_exists($path . '.php')) {
            setContext('app', $appName);
            return $path;
        }
        
        foreach (wget('Application')->getModules() as $module) {
            $path = includeDir() . 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $resourceType . DIRECTORY_SEPARATOR . $resource;
            
            if (file_exists($path . '.php')) {
                setContext('module', $module);
                return $path;
            }
        }

        return false;
    }

    private static function getLocalePath($resource, $resourceType) {
        $appName = wget('App:Name');
        $path = includeDir() . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR . $appName . DIRECTORY_SEPARATOR . $resourceType . DIRECTORY_SEPARATOR . $resource;
        
        if (whas(LOCALE)) {
            $locale = wget(LOCALE);
            
            if (file_exists($path . '_' . $locale . '.locale')) {
                return $path . '_' . $locale . '.locale';
            }
        }
        
        if (file_exists($path . '.locale')) {
            return $path . '.locale';
        }
        
        foreach (wget('Application')->getModules() as $module) {
            $path = $path = includeDir() . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $resourceType . DIRECTORY_SEPARATOR . $resource;
            
            if (whas(LOCALE)) {
                $locale = wget(LOCALE);
                
                if (file_exists($path . '_' . $locale . '.locale')) {
                    return $path . '_' . $locale . '.locale';
                }
            }
            
            if (file_exists($path . '.locale')) {
                return $path . '.locale';
            }
        }
        
        return false;
    }

}
