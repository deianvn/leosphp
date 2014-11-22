<?php

class Models_CreateTables extends LSAction {
    
    public function init() {
        
    }
    
    public function execute($module = null, $model = null) {
        $modelMgr = new ModelMgr();

        if ($module === null && $model === null) {
            $result = $modelMgr->createModelsTables();

            if ($result !== true) {
                wput('Error', $result);
            }
        } else if ($model === null) {
            if (!preg_match("/^[a-zA-Z0-9-]+$/", $module)) {
                $this->page('GeneralError');
            }

            $result = $modelMgr->createModuleModelsTables($module);

            if ($result !== true) {
                wput('Error', $result);
            }
        } else {
            if (!preg_match("/^[a-zA-Z0-9-]+$/", $model) || !preg_match("/^[a-zA-Z0-9-]+$/", $module)) {
                $this->page('GeneralError');
            }

            $modelMgr = new ModelMgr();
            $result = $modelMgr->createModelTable($model, $module);

            if ($result !== true) {
                wput('Error', $result);
            }
        }

        $this->scriptlet('LoadModels');
        $this->page('ModelsPage');
    }
    
}

?>