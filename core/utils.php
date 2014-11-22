<?php

namespace ls\core;

function includeDir() {
    return INCLUDE_DIR;
}

function hasModule($module) {
    return whas('App:ModulesArray') && is_array(wget('App:ModulesArray')) && in_array($module, wget('App:ModulesArray'));
}

function wput($key, $value, $persist = false) {
    global $lswallet, $lssessionwallet;
    if ($persist === true) {
        $lssessionwallet[$key] = $value;

        if (isset($lswallet[$key])) {
            unset($lswallet[$key]);
        }
    } else {
        $lswallet[$key] = $value;

        if (isset($lssessionwallet[$key])) {
            unset($lssessionwallet[$key]);
        }
    }
}

function wmap($sourceKey, $destinationKey, $persist = false) {
    if (whas($sourceKey)) {
        wput($destinationKey, wget($sourceKey), $persist);
    }
}

function wmove($sourceKey, $destinationKey, $persist = false) {
    if (whas($sourceKey)) {
        $value = wget($sourceKey);
        wremove($sourceKey);
        wput($destinationKey, $value, $persist);
    }
}

function wget($key) {
    global $lswallet, $lssessionwallet;

    if (isset($lswallet[$key])) {
        return $lswallet[$key];
    } else if (isset($lssessionwallet[$key])) {
        return $lssessionwallet[$key];
    }

    return null;
}

function whas($key) {
    global $lswallet, $lssessionwallet;
    return isset($lswallet[$key]) || isset($lssessionwallet[$key]);
}

function wremove($key) {
    global $lswallet, $lssessionwallet;

    if (isset($lswallet[$key])) {
        unset($lswallet[$key]);
    } else if (isset($lssessionwallet[$key])) {
        unset($lssessionwallet[$key]);
    }
}

function wvalidate($key, $validator) {
    if (!whas($key)) {
        return false;
    }

    return $validator->validate(wget($key));
}

function wequals($key, $value, $strict = true) {
    if ($strict === true) {
        return whas($key) && wget($key) === $value;
    } else {
        return whas($key) && wget($key) == $value;
    }
}

function wclear() {
    global $lswallet, $lssessionwallet;
    $lswallet = array();
    $lssessionwallet = array();
}

function setContext($type, $name) {
    global $lscontextstack;
    $lscontextstack[] = new LSContext($type, $name);
}

function clearContext() {
    global $lscontextstack;
    array_pop($lscontextstack);
}

/**
* return LSContext
*/
function context() {
    global $lscontextstack;
    return end($lscontextstack);
}

function cget($key) {
    $context = context();
    $containerType = $context->getContainerType();
    $containerName = $context->getContainerName();
    
    if ($containerType === 'module' && whas('Module:' . $containerName . ':Config:' . $key)) {
        return wget('Module:' . $containerName . ':Config:' . $key);
    }
    
    return NULL;
}

function chas($key) {
    $context = context();
    $type = $context->getType();
    $name = $context->getName();
    
    if ($type === 'module' && whas('Module:' . $name . ':Config:' . $key)) {
        return true;
    }
    
    return false;
}

function useLib($name, $module = null) {
    $name = str_replace('/', DIRECTORY_SEPARATOR, $name);
    $name = str_replace("\\", DIRECTORY_SEPARATOR, $name);
    
    if ($module !== null && hasModule($module)) {
        $path = includeDir() . 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . $name . '.php';
        
        if (file_exists($path)) {
            setContext('module', $module);
            require_once $path;
            clearContext();
        }
    } else {
        $path = includeDir() . 'apps' . DIRECTORY_SEPARATOR . wget('App:Name') . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . $name . '.php';
        
        if (file_exists($path)) {
            setContext('app', wget('App:Name'));
            require_once $path;
            clearContext();
        } else {
            foreach (wget('Application')->getModules() as $module) {
                $path = includeDir() . 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . $name . '.php';

                if (file_exists($path)) {
                    setContext('module', $module);
                    require_once $path;
                    clearContext();
                }
            }
        }
    }

    LSLogger::error('Library [' . $name . '] could not be located');
    return null;
}

function useModel($name, $module = null) {
    $name = str_replace('/', DIRECTORY_SEPARATOR, $name);
    $name = str_replace("\\", DIRECTORY_SEPARATOR, $name);
    $appName = wget('App:Name');
    $paths = null;
    
    if ($module !== null && hasModule($module)) {
        $path = includeDir() . 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . $name . '.php';
        
        if (file_exists($path)) {
            setContext('module', $name);
            require_once $path;
            $path = includeDir() . 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'managers' . DIRECTORY_SEPARATOR . $name . 'Mgr.php';

            if (file_exists($path)) {
                require_once $path;
            }
            
            $path = includeDir() . 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'factories' . DIRECTORY_SEPARATOR . $name . 'Factory.php';

            if (file_exists($path)) {
                require_once $path;
            }
            
            clearContext();
            return;
        }
    } else {
        foreach (wget('Application')->getModules() as $module) {
            $path = includeDir() . 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . $name . '.php';

            if (file_exists($path)) {
                setContext('module', $name);
                require_once $path;
                $path = includeDir() . 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'managers' . DIRECTORY_SEPARATOR . $name . 'Mgr.php';

                if (file_exists($path)) {
                    require_once $path;
                }
                
                $path = includeDir() . 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'factories' . DIRECTORY_SEPARATOR . $name . 'Factory.php';

                if (file_exists($path)) {
                    require_once $path;
                }
                
                clearContext();
                return;
            }
        }
    }

    LSLogger::error('Model [' . $name . '] could not be located');
}

function l($key) {
    global $lsloc;

    if (isset($lsloc[$key])) {
        return $lsloc[$key];
    } else {
        return '[' . $key . '] N/A';
    }
}

function ll($key) {
    global $lsloc;

    if (isset($lsloc[$key])) {
        echo $lsloc[$key];
    } else {
        echo '[' . $key . '] N/A';
    }
}

function redirect($target) {
    global $lssessionwallet, $privateSessionID;
    session_name('Private');
    session_id($privateSessionID);
    session_start();
    $_SESSION['LSSessionWallet'] = $lssessionwallet;
    session_write_close();

    LSLogger::info('Redirecting to: ' . $target);
    header('Location: ' . $target);
    exit(0);
}

function getScriptletPath($name) {
    return LSWorkflowMgr::getScriptletPath($name);
}

function snippet($name) {
    /*
    global $lscache;

    if (isset($lscache[$name])) {
        echo $lscache[$name];
        LSLogger::debug('Using cachelet data');
        return;
    }
    
    $action = wget('Request:Action');
    $cachelet = $action->getCacheletBySnippet($name);

    if ($cachelet !== null) {
        ob_start();
    }
    */
    $path = LSWorkflowMgr::getSnippetPath($name);
    /*
    if ($cachelet !== false) {
        $cachePath = LSCacheMgr::createCacheletCachePath($cachelet);

        if (!file_exists($cachePath)) {
            $fo = fopen($cachePath, 'w');

            if ($fo !== false && flock($fo, LOCK_EX)) {
                $cache = ob_get_contents();
                fwrite($fo, $cache);
                fclose($fo);
                LSLogger::info('Cachelet data written');
            }
        }

        ob_end_flush();
    }
    */
    
    return $path;
}

function page($name) {
    global $lssessionwallet, $privateSessionID;
    session_name('Private');
    session_id($privateSessionID);
    session_start();
    $_SESSION['LSSessionWallet'] = $lssessionwallet;
    session_write_close();

    $pagePath = LSWorkflowMgr::getPagePath($name);
    $action = wget('App:CurrentAction');
    
    /*
    if ($action->isPageCacheEnabled() === true) {
        $cachePath = LSCacheMgr::createActionCachePath();

        if (!file_exists($cachePath)) {
            $fo = fopen($cachePath, 'w');

            if (flock($fo, LOCK_EX)) {
                $cache = ob_get_contents();
                fwrite($fo, $cache);
                fclose($fo);
                LSLogger::info('Page cache written');
            }
        }

        ob_end_flush();
    }
    */

    return $pagePath;
}
