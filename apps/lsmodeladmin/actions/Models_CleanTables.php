<?php

class Models_CleanTables extends LSAction {
    
    public function init() {
        
    }
    
    public function execute($module = null, $model = null) {
        $modelMgr = new ModelMgr();

        if ($module === null && $model === null) {
            $modelMgr->dropModelsTables();
        } else if ($model === null) {
            if (!preg_match("/^[a-zA-Z0-9-]+$/", $module)) {
                $this->page('GeneralError');
            }

            $modelMgr->dropModuleModelsTables($module);
        } else {
            if (!preg_match("/^[a-zA-Z0-9-]+$/", $model) || !preg_match("/^[a-zA-Z0-9-]+$/", $module)) {
                $this->page('GeneralError');
            }

            $modelMgr->dropModelTable($model, $module);
        }

        $this->scriptlet('LoadModels');
        $this->page('ModelsPage');
    }
    
}

?>