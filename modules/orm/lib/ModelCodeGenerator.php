<?php

namespace ls\orm;

class ModelCodeGenerator {
    
    /**
     *
     * @var \ls\internal\Container
     */
    private $container;
    
    public function __construct(\ls\internal\Container $container) {
        $this->container = $container;
    }
    
    /**
     * 
     * @return \ls\internal\Container
     */
    public function getContainer() {
        return $this->container;
    }
    
    /**
     * 
     * @param \ls\orm\ModelDescriptor $descriptor
     * @return boolean
     */
    public function generate($descriptorName) {
        $generatedCode = array();
        $application = $this->getContainer()->getApplication();
        $modelTemplateResourceInfo = $application->locateResource('model.php', 'template', '.template');
        
        if ($modelTemplateResourceInfo === false) {
            return false;
        }
        
        $modelTemplate = file_get_contents($modelTemplateResourceInfo->getPath());
        $descriptorResourceInfo = $application->locateResource($descriptorName, 'model');
        
        if ($descriptorResourceInfo === false) {
            return false;
        }
        
        //$generatePath = $descriptor->getContainer()->getPath() . 'orm/' . $this->getModelName();
        
        return true;
    }
    
    /**
     * 
     * @param string $tag
     * @param string $code
     * @param string $template
     */
    public function setCode($tag, $code, &$template) {
        $template = str_replace($tag, $code, $template);
    }
    
}
