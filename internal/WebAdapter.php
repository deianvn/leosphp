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
        $this->handleRequest();
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
    
    private function handleRequest() {
        $segmentsCount = count($this->segments);
        $applicationName = null;
        $resourceUri = '';
        $actionName = null;
        $parameters = array();
        $this->setRequestParameterNamesFromUri($applicationName, $actionName, $parameters, $resourceUri, $segmentsCount);
        
        try {
            $application = $this->getApplication($applicationName);
            $resourceInfo = $this->getResource($resourceUri, $application);
            
            if ($resourceInfo !== false) {
                $this->handleResourceRequest($application, $resourceInfo);
            } else {
                $this->handleActionRequest($application, $actionName, $parameters);
            }
        } catch (\Exception $e) {
            header("HTTP/1.0 404 Not Found");
            exit;
        }
    }
    
    /**
     * 
     * @param \ls\internal\Application $application
     * @param \ls\internal\ResourceInfo $resourceInfo
     */
    private function handleResourceRequest($application, $resourceInfo) {
        set_time_limit(0);
        
        if ($application->isResourceCachingEnabled()) {
            $this->cacheResource($application, $resourceInfo);
        }
        
        if ($application->isAllowResourceSeeking() === true) {
            $this->seekResource($resourceInfo);
        } else {
            $this->echoResource($resourceInfo);
        }
    }
    
    /**
     * 
     * @param \ls\internal\Application $application
     * @param \ls\internal\ResourceInfo $resourceInfo
     */
    private function cacheResource($application, $resourceInfo) {
        $path = BASE_DIR . 'cache/resources/' . $application->getName() . '/' . $resourceInfo->getName();
        
        if (file_exists($path)) {
            return;
        }
        
        $dir = dirname($path);
        
        if (file_exists($dir) === false) {
            mkdir($dir, 0777, true);
        }
        
        if (symlink($resourceInfo->getPath(), $path) === false) {
            copy($resourceInfo->getPath(), $path);
        }
    }

    /**
     * 
     * @param \ls\internal\ResourceInfo $resourceInfo
     */
    private function echoResource($resourceInfo) {
        $path = $resourceInfo->getPath();
        $ctype = $this->getContentType($path);
        $lastModified = filemtime($path);
        $etagFile = md5_file($path);
        $ifModifiedSince = filter_input(INPUT_SERVER, 'HTTP_IF_MODIFIED_SINCE');
        $etagHeader = filter_input(INPUT_SERVER, 'HTTP_IF_NONE_MATCH');
        $this->setResourceResponceHeaders($lastModified, $etagFile, $etagHeader, $ifModifiedSince, $ctype);
        $f = fopen($path, 'rb');
        $chunkSize = 8192;
        
        while (!feof($f)) {
            echo fread($f, $chunkSize);
            flush();
        }
        
        fclose($f);
        exit(0);
    }
    
    private function setResourceResponceHeaders($lastModified, $etagFile, $etagHeader, $ifModifiedSince, $ctype) {
        header("Last-Modified: " . gmdate("D, d M Y H:i:s", $lastModified) . " GMT");
        header("Etag: $etagFile");
        header('Cache-Control: public');
        
        if (($ifModifiedSince !== false && strtotime($ifModifiedSince) == $lastModified) || $etagHeader == $etagFile) {
            header("HTTP/1.1 304 Not Modified");
            exit;
        }
        
        header('Content-type: ' . $ctype);
        header('Expires: ' . date('D, d M Y H:i:s', time() + (60 * 60 * 24 * 45)) . ' GMT');
    }
    
    /**
     * 
     * @param \ls\internal\ResourceInfo $resourceInfo
     */
    private function seekResource($resourceInfo) {
        $range = filter_input(INPUT_SERVER, 'HTTP_RANGE');
        
        if ($range === null || $range === false) {
            $this->echoResource($resourceInfo);
            exit;
        }
        
        $path = $resourceInfo->getPath();
        $fileSize = filesize($path);
        $ranges = $this->getRanges($range, $fileSize);
        $ctype = $this->getContentType($path);
        $this->setSeekResourceResponceHeaders($ctype, $ranges, $fileSize);
        $this->echoSeekResource($path, $ranges);
        exit;
    }
    
    private function echoSeekResource($path, $ranges) {
        $f = fopen($path, 'rb');
        $chunkSize = 8192;
        fseek($f, $ranges[0]);
        
        while (!feof($f)) {
            if (ftell($f) >= $ranges[1]) {
                break;
            }

            echo fread($f, $chunkSize);
            flush();
        }
        
        fclose($f);
    }
    
    private function getRanges($range, $fileSize) {
        $ranges = array_map('intval', explode('-', substr($range, 6)));
        
        if(!$ranges[1]) {
            $ranges[1] = $fileSize - 1;
        }
        
        return $ranges;
    }
    
    private function setSeekResourceResponceHeaders($ctype, $ranges, $fileSize) {
        header('Content-type: ' . $ctype);
        header('HTTP/1.1 206 Partial Content');
        header('Accept-Ranges: bytes');
        header('Content-Length: ' . ($ranges[1] - $ranges[0]));
        header(sprintf('Content-Range: bytes %d-%d/%d', $ranges[0], $ranges[1], $fileSize));
    }
    
    /**
     * 
     * @param string $path
     * @return string
     */
    private function getContentType($path) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $ctype = finfo_file($finfo, $path);
        finfo_close($finfo);
        
        if ($ctype === false) {
            $ctype = 'text/plain';
        }
        
        return $ctype;
    }

    private function handleActionRequest($application, $actionName, $parameters) {
        $action = $this->getAction($actionName, $application);

        if (count($parameters) === 0) {
            $parameters = $application->getDefaultActionParameters();
        }

        $this->request = new Request($application, $action, $parameters);
        $this->ls->setRequest($this->request);
        $this->execute();
    }
    
    /**
     * 
     * @param string $resourceUri
     * @param \ls\internal\Application $application
     */
    private function getResource($resourceUri, $application) {
        return $application->locateResource($resourceUri, 'webroot', '');
    }
    
    /**
     * 
     * @param type $applicationName
     * @param type $actionName
     * @param type $parameters
     * @param type $segmentsCount
     */
    private function setRequestParameterNamesFromUri(&$applicationName, &$actionName, &$parameters, &$resourceUri, $segmentsCount) {
        for ($i = 0; $i < $segmentsCount; $i++) {
            switch ($i) {
                case 0 :
                    $applicationName = $this->segments[$i];
                    break;
                case 1 :
                    $actionName = $this->segments[$i];
                    $resourceUri .= '/' . $this->segments[$i];
                    break;
                default :
                    $parameters[] = $this->segments[$i];
                    $resourceUri .= '/' . $this->segments[$i];
            }
        }
        
        $resourceUri = trim($resourceUri, '/');
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
        
        $application = new Application($applicationName, $this->ls);
        $application->loadConfigurationFile();
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
        $action->loadAutoIncludeFile();
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
