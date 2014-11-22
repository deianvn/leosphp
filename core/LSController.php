<?php

namespace ls\core;

session_name('Private');
session_start();
$privateSessionID = session_id();

if (isset($_SESSION['LSSessionWallet'])) {
    $lssessionwallet = $_SESSION['LSSessionWallet'];
} else {
    $lssessionwallet = array();
}

session_write_close();
$uri = wget('Request:RawURI');

if ($uri === 'index.php') {
    $uri = '';
}

$uri = LSRouter::rewrite($uri);

if (strlen($uri) === 0) {
    if (whas('Default:App')) {
        $uri .= wget('Default:App');
    } else {
        LSLogger::error('Invalid request uri');
        exit(1);
    }
}

$uriChunks = explode('/', $uri);

if (count($uriChunks) < 1) {
    LSLogger::error('Insufficient parameters');
    exit(1);
}

$appName = urldecode($uriChunks[0]);
wput('App:Name', $appName);
LSRouter::mapApplication();
$appName = wget('App:Name');
unset($uriChunks[0]);
$uriChunks = array_values($uriChunks);

if (!file_exists(INCLUDE_DIR . 'apps' . DIRECTORY_SEPARATOR . $appName)) {
    echo 'Application could not be located: ' . $appName;
    exit(1);
}

$application = new LSApplication($appName);

if ($application->isEnabled() === false) {
    LSLogger::error('Application disabled: ' . $appName);
    exit(1);
}

if (LSResourceMgr::locateResource(DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $uriChunks)) === true) {
    if ($application->isCacheResourcesEnabled() === true) {
        LSResourceMgr::cacheResource();
    }
    
    LSResourceMgr::echoResource();
}

$uriChunks = explode('/', $uri);

if (count($uriChunks) === 1) {
    $actionFound = false;
    
    foreach (array_keys($_POST) as $param) {
        if ($param{0} === '@') {
            $uri .= '/' . substr($param, 1);
            $actionFound = true;
            break;
        }
    }
    
    if ($application->getDefaultAction() != null && $actionFound === false) {
        $uri .= '/' . urlencode($application->getDefaultAction());
        
        if (count($application->getDefaultActionParams() > 0)) {
            foreach ($application->getDefaultActionParams() as $param) {
                $uri .= '/' . urlencode($param);
            }
        }
        
    }
    
    if ($application->isDefaultActionRedirectEnabled() === true) {
        redirect($uri);
    }

    $uriChunks = explode('/', $uri);
}

if (count($uriChunks) < 2) {
    LSLogger::error('Insufficient parameters');
    exit(1);
}

wput('Request:URI', $uri);
$control = urldecode($uriChunks[1]);
unset($uriChunks[0]);
unset($uriChunks[1]);
wput('Request:Action', $control);
LSRouter::mapAction();
$uriChunks = array_values($uriChunks);

for ($i = 0; $i < count($uriChunks); $i++) {
    $uriChunks[$i] = urldecode($uriChunks[$i]);
}

wput('Request:Params', $uriChunks);

if ($application->isLocalizationEnabled() === true && $application->getDefaultLocale() != null && !whas(LOCALE)) {
    wput(LOCALE, $application->getDefaultLocale());
}

if (!defined('BASE_URL')) {
    if (isset($_SERVER['HTTP_HOST'])) {
        $baseUrl = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
        $baseUrl .= '://' . $_SERVER['HTTP_HOST'];
        $baseUrl .= str_replace('static/' . basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        $pos = strrpos($uri, $_SERVER['REQUEST_URI']);
		
        if($pos !== false) {
            $baseUrl .= '/' . substr_replace($_SERVER['REQUEST_URI'], $uri, $pos);
        }
        
        if(substr($baseUrl, -1) == '/') {
            $baseUrl = substr($baseUrl, 0, -1);
        }
        
        $baseUrl = preg_replace('/\/index.php$/', '', $baseUrl);
        
        define('BASE_URL', $baseUrl);
    } else {
        define('BASE_URL', 'http://localhost');
    }
}

wput('Request:BaseURL', BASE_URL);
LSActionMgr::executeCurrentAction();
