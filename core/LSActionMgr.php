<?php

namespace ls\core;

class LSActionMgr {
    public static function executeCurrentAction() {
        $name = wget('Request:Action');
        $params = wget('Request:Params');
        LSActionMgr::executeAction($name, $params);
    }
    
    public static function executeAction($name, $params) {
        $path = LSActionMgr::locateAction($name);
        
        if ($path === null) {
            clearContext();
            LSLogger::error('Action ' . $name . ' could not be located');
            LSHttp::send404();
        }
        
        require $path;
        $actionClass = new \ReflectionClass($name);
        $actionObject = $actionClass->newInstance();        
        wput('App:CurrentAction', $actionObject);
        
        if ($actionClass->hasMethod('init')) {
            $actionObjectInitMethod = $actionClass->getMethod('init');
            $actionObjectInitMethod->invoke($actionObject);
        }
        
        foreach ($_GET as $key => $value) {
            if ($actionObject->isFormParamRegistered($key, 'get') === true) {
                wput($key, LSConverter::instance()->convert($actionObject->getFormParamType($key), $value));
            }
        }
        
        foreach ($_POST as $key => $value) {
            if ($actionObject->isFormParamRegistered($key, 'post') === true) {
                wput($key, LSConverter::instance()->convert($actionObject->getFormParamType($key), $value));
            }
        }
        
        if ($actionClass->hasMethod('execute')) {
            $actionObjectExecuteMethod = $actionClass->getMethod('execute');
            $reqNumber = $actionObjectExecuteMethod->getNumberOfRequiredParameters();
            $allNumber = $actionObjectExecuteMethod->getNumberOfParameters();
            $number = count($params);
            
            if ($number >= $reqNumber && $number <= $allNumber) {
                $actionObjectExecuteMethod->invokeArgs($actionObject, $params);
            } else {
                LSLogger::error('Action ' . $name . ' has wrong number of parameters');
                LSHttp::send404();
            }
        }
        
        clearContext();
    }
    
    public static function locateAction($name) {
        $app = wget('App:Name');
        $path = includeDir() . 'apps' . DIRECTORY_SEPARATOR . $app . DIRECTORY_SEPARATOR . 'actions' . DIRECTORY_SEPARATOR . $name . '.php';
        
        if (!file_exists($path)) {
            $path = null;
            $modules = wget('Application')->getModules();
            
            foreach ($modules as $module) {
                $tempPath = includeDir() . 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'actions' . DIRECTORY_SEPARATOR . $name . '.php';
                
                if (file_exists($tempPath)) {
                    $path = $tempPath;
                    setContext('module', $module);
                    break;
                }
            }
        } else {
            setContext('app', $app);
        }
        
        return $path;
    }
}
