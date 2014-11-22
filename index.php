<?php

namespace ls\core;

define('INCLUDE_DIR', getcwd() . DIRECTORY_SEPARATOR);
require INCLUDE_DIR . 'core/setup.php';
$uri = '';

if (isset($_GET['lsuri'])) {
    $uri = $_GET['lsuri'];
}

wput('Request:RawURI', $uri);
$uri = $_GET['lsuri'];
$path = INCLUDE_DIR . '/cache/page/' . $uri;

if (file_exists($path . '.cache') && is_file($path . '.cache') && file_exists($path . '.header') && is_file($path . '.header')) {
    if (defined(ADVANCED_FULL_PAGE_CACHE_EXPIRATION_TIME) && time() - filemtime($path) < ADVANCED_FULL_PAGE_CACHE_EXPIRATION_TIME) {
        header('Content-type: ' . file_get_contents($path . '.header'));
        echo file_get_contents($path . '.cache');
        LSLogger::debug('Using full page cache file: ' . $path);
        exit(0);
    }

    unlink($path);
}

require INCLUDE_DIR . 'core/LSController.php';
