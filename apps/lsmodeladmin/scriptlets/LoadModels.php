<?php

$modulesPath = INCLUDE_DIR . 'modules/';
$modules = array();
$handle = opendir($modulesPath);

if ($handle !== false) {
    while (($module = readdir($handle)) !== false) {
        if ($module !== '.' && $module !== '..' && !LSStringUtils::startsWith($module, '.')) {
            $modules[$module] = array();
        }
    }
} else {
    $error = l('CanNotOpenModulesFolder') . '!';
    
    if (whas('Error')) {
        wput(wget('Error') . '<br />' . $error);
    } else {
        wput('Error', $error);
    }
}

foreach ($modules as $module => &$models) {
    $path = INCLUDE_DIR . 'modules/' . $module . '/models/';
    $handle = opendir($path);
    
    if ($handle !== false) {
        while (($model = readdir($handle)) !== false) {
            if (LSStringUtils::endsWith($model, '.model') === true) {
                $models[] = str_replace('.model', '', $model);
            }
        }
    }
    
    closedir($handle);
}

wput('Modules', $modules);
