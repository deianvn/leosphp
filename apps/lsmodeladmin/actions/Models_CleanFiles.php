<?php

class Models_CleanFiles extends LSAction {
    
    public function init() {
        
    }
    
    public function execute($module = null, $model = null) {
        $modelMgr = new ModelMgr();

        if ($module === null && $model === null) {
            $modelMgr->cleanModels();
        } else if ($model === null) {
            if (!preg_match("/^[a-zA-Z0-9-]+$/", $module)) {
                $this->page('GeneralError');
            }

            $modelMgr->cleanModuleModels($module);
        } else {
            if (!preg_match("/^[a-zA-Z0-9-]+$/", $model) || !preg_match("/^[a-zA-Z0-9-]+$/", $module)) {
                $this->page('GeneralError');
            }

            $modelMgr->cleanModel($model, $module);
        }
        
        $this->scriptlet('LoadModels');
        $this->page('ModelsPage');
    }
    
}

?>