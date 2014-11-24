<?php

namespace ls\internal;

class Request {
    
    /**
     *
     * @var \ls\internal\Application 
     */
    private $application;
    
    /**
     *
     * @var \ls\internal\Action 
     */
    private $action;
    
    /**
     *
     * @var string[] 
     */
    private $parameters;
    
    /**
     *
     * @var boolean 
     */
    private $redirect;
    
    /**
     * 
     * @param string $application
     * @param string $action
     * @param string[] $parameters
     * @param boolean $redirect
     */
    public function __construct($application, $action, $parameters, $redirect = false) {
        $this->application = $application;
        $this->action = $action;
        $this->parameters = $parameters;
        $this->redirect = $redirect;
    }
    
    /**
     * 
     * @return string
     */
    public function getApplication() {
        return $this->application;
    }

    /**
     * 
     * @return string
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * 
     * @return string
     */
    public function getParameters() {
        return $this->parameters;
    }

    /**
     * 
     * @return string
     */
    public function isRedirect() {
        return $this->redirect;
    }
    
}
