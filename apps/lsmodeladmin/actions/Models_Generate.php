<?php

class Models_Generate extends LSAction {
    
    public function init() {
        $this->registerFormParam('string', 'Param_Module', 'post');
        $this->registerFormParam('string', 'Param_Model', 'post');
    }
    
    public function execute() {
        $modelMgr = new ModelMgr();
        
        if (!whas('Param_Module') && !whas('Param_Model')) {
            $modelMgr->generateModels();
        } else if (!whas('Param_Model')) {
            $module = wget('Param_Module');
            
            if (!preg_match("/^[a-zA-Z0-9-_]+$/", $module)) {
                $this->page('GeneralError');
            }
            
            $modelMgr->generateModuleModels($module);
        } else {
            $module = wget('Param_Module');
            $model = wget('Param_Model');
            
            if (!preg_match("/^[a-zA-Z0-9-_]+$/", $model) || !preg_match("/^[a-zA-Z0-9-_]+$/", $module)) {
                $this->page('GeneralError');
            }
            
            $modelMgr = new ModelMgr();
            $modelMgr->generateModel($model, $module);
        }
        
        $this->scriptlet('LoadModels');
        $this->page('ModelsPage');
    }
    
}

?>