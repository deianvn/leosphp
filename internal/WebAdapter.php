<?php

namespace ls\internal;

class WebAdapter {
    
    private $uri;
    
    private $segments;
    
    private $request;
    
    private $router;
    
    private $actionClass;
    
    /**
     *
     * @var ls\internal\LS; 
     */
    private $ls;
    
    public function __construct($uri) {
        $this->uri = $uri;
        $this->createLs();
        $this->createRouter();
        $this->route();
        $this->createRequest();
        $this->ls->setRequest($this->request);
        $this->execute();
    }
    
    private function createLs() {
        $this->ls = new LS();
        $this->ls->loadConfigurationFile();
    }
    
    private function createRouter() {
        $this->router = new Router($this->ls);
        $this->router->loadRoutesFile();
    }
    
    private function route() {
        $this->segments = explode('/', $this->router->route($this->uri));
    }
    
    private function createRequest() {
        $segmentsCount = count($this->segments);
        $applicationName = null;
        $actionName = null;
        $parameters = array();
        $this->setRequestParameterNamesFromUri($applicationName, $actionName, $parameters, $segmentsCount);
        
        try {
            $application = $this->getApplication($applicationName);
            $action = $this->getAction($actionName, $application);
            $this->request = new Request($application, $action, $parameters);
        } catch (\Exception $e) {
            header("HTTP/1.0 404 Not Found");
            exit;
        }
    }
    
    /**
     * 
     * @param type $applicationName
     * @param type $actionName
     * @param type $parameters
     * @param type $segmentsCount
     */
    private function setRequestParameterNamesFromUri(&$applicationName, &$actionName, &$parameters, $segmentsCount) {
        for ($i = 0; $i < $segmentsCount; $i++) {
            switch ($i) {
                case 0 :
                    $applicationName = $this->segments[$i];
                    break;
                case 1 :
                    $actionName = $this->segments[$i];
                    break;
                default :
                    $parameters[] = $this->segments[$i];
            }
        }
    }
    
    /**
     * 
     * @param type $applicationName
     * @return \ls\internal\Application
     * @throws MalformedRequestException
     */
    private function getApplication($applicationName) {
        if ($applicationName === null) {
            if ($this->ls->getDefaultApplicationName() !== null) {
                $applicationName = $this->ls->getDefaultApplicationName();
            } else {
                throw new MalformedRequestException('Could not locate application from uri: ' . $this->uri);
            }
        }
        
        return $this->createApplciation($applicationName);
    }
    
    /**
     * 
     * @param string $applicationName
     * @return \ls\internal\Application
     */
    private function createApplciation($applicationName) {
        $application = new Application($applicationName, $this->ls);
        $application->loadConfigurationFile();
        $application->loadAutoIncludeFile();
        
        return $application;
    }
    
    /**
     * 
     * @param string $actionName
     * @param \ls\internal\Application $application
     * @return \ls\internal\Action 
     * @throws MalformedRequestException
     * @throws ResourceNotFoundException
     */
    private function getAction($actionName, $application) {
        if ($actionName === null) {
            if ($application->getDefaultActionName() !== null) {
                $actionName = $application->getDefaultActionName();
            } else {
                throw new MalformedRequestException('Could not locate action from uri: ' . $this->uri);
            }
        }
        
        $resourceInfo = $application->locateResource($actionName, 'action');
        
        if ($resourceInfo === false) {
            throw new ResourceNotFoundException('Action could not be located: ' . $actionName);
        }
        
        $action = $this->createAction($resourceInfo);
        
        return $action;
    }
    
    /**
     * 
     * @param \ls\internal\ResourceInfo $resourceInfo
     * @return \ls\internal\Action 
     */
    private function createAction($resourceInfo) {
        require_once $resourceInfo->getPath();
        $this->actionClass = new \ReflectionClass($resourceInfo->getName());
        $action = $this->actionClass->newInstance($resourceInfo->getName(), $resourceInfo->getContainer());
        
        return $action;
    }
    
    /**
     * 
     */
    private function execute() {
        $action = $this->request->getAction();
        $action->init();
        
        if ($this->actionClass->hasMethod('execute')) {
            $method = $this->actionClass->getMethod('execute');
            $reqNumber = $method->getNumberOfRequiredParameters();
            $allNumber = $method->getNumberOfParameters();
            $number = count($this->request->getParameters());
            
            if ($number >= $reqNumber && $number <= $allNumber) {
                $this->executeAction($action, $this->request->getParameters(), $method);
                
                exit;
            }
        }
    }
    
    /**
     * 
     * @param \ls\internal\Action $action
     * @param string[] $parameters
     * @param \ReflectionMethod $method
     */
    private function executeAction($action, $parameters, $method) {
        try {
            $method->invokeArgs($action, $parameters);
        } catch (\InvocationException $e) {
            
        }
    }
    
}
