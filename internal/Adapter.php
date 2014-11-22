<?php

namespace ls\internal;

abstract class Adapter {
    
    protected function executeServlet($resourceInfo, $arguments) {
        require $resourceInfo->getPath();
        $servletClass = new \ReflectionClass($resourceInfo->getName());
        $servletObject = $servletClass->newInstance($resourceInfo->getName(), $resourceInfo->getContainer());
        $servletObject->init();
        
        if ($servletClass->hasMethod('execute')) {
            $servletObjectExecuteMethod = $servletClass->getMethod('execute');
            $reqNumber = $servletObjectExecuteMethod->getNumberOfRequiredParameters();
            $allNumber = $servletObjectExecuteMethod->getNumberOfParameters();
            $number = count($arguments);
            
            if ($number >= $reqNumber && $number <= $allNumber) {
                try {
                    $servletObjectExecuteMethod->invokeArgs($servletObject, $arguments);
                    return true;
                } catch (InvocationException $e) {}
            }
        }
        
        return false;
    }
    
}
