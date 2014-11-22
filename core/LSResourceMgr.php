<?php

namespace ls\core;

class LSResourceMgr {
    
    private static $uri;
    private static $resource;
    
    public static function cacheResource() {
        $path = static::$resource;
        
        if ($path === null) {
            LSHttp::send404();
        }
        
        $uri = ltrim(static::$uri, DIRECTORY_SEPARATOR);
        $dirs = explode(DIRECTORY_SEPARATOR, $uri);
        $path = includeDir() . 'static' . DIRECTORY_SEPARATOR . wget('App:Name');
        
        for ($i = 0; $i < count($dirs); $i++) {
            if (!file_exists($path)) {
                mkdir($path);
            }
            
            $path .= DIRECTORY_SEPARATOR . $dirs[$i];
        }
        
        copy(static::$resource, $path);
        
    }
    
    public static function echoResource() {
        $path = static::$resource;
        
        if ($path === null) {
            LSHttp::send404();
        }

        $ctype = LSResourceMgr::getResourceContentType($path);

        if ($ctype === false) {
            $ctype = 'text/plain';
        }

        $lastModified = filemtime($path);
        $etagFile = md5_file($path);
        $ifModifiedSince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
        $etagHeader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);
        header("Last-Modified: " . gmdate("D, d M Y H:i:s", $lastModified) . " GMT");
        header("Etag: $etagFile");
        header('Cache-Control: public');

        if (($ifModifiedSince !== false && strtotime($ifModifiedSince) == $lastModified) || $etagHeader == $etagFile) {
            header("HTTP/1.1 304 Not Modified");
            exit;
        }
        
        header('Content-type: ' . $ctype);
        header('Expires: ' . date('D, d M Y H:i:s', time() + (60 * 60 * 24 * 45)) . ' GMT');
        echo file_get_contents($path);
        exit(0);
    }

    public static function downloadResource() {
        $path = static::$resource;

        if ($path === null) {
            LSHttp::send404();
        }

        $ctype = LSResourceMgr::getResourceContentType($path);

        if ($ctype === false) {
            $ctype = 'application/force-download';
        }

        $fsize = filesize($path);
        header("Pragma: public"); // required 
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false); // required for certain browsers 
        header("Content-Type: $ctype");
        header("Content-Disposition: attachment; filename=\"" . basename($path) . "\";");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . $fsize);
        echo file_get_contents($path);
        exit(0);
    }
    
    public static function locateCurrentResource() {
        $params = wget('Request:Params');
        return LSResourceMgr::locateResource(DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $params));
    }

    public static function locateResource($uri) {
        if (strlen($uri) === 0) {
            return false;
        }
        
        $app = wget('App:Name');
        $path = includeDir() . 'apps' . DIRECTORY_SEPARATOR . $app . DIRECTORY_SEPARATOR . 'webroot' . $uri;
        
        if (!file_exists($path) || is_dir($path)) {
            $path = null;
            $modules = wget('Application')->getModules();

            foreach ($modules as $module) {
                $tempPath = includeDir() . 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'webroot' . $uri;

                if (file_exists($tempPath) && is_file($tempPath)) {
                    $path = $tempPath;
                    break;
                }
            }
        }
        
        if ($path !== null) {
            static::$resource = $path;
            static::$uri = $uri;
            return true;
        }
        
        return false;
    }

    public static function getResourceContentType($path) {
        if (DETERMINE_IMT_BY_EXTENSION === true) {
            $pathParts = pathinfo($path);
            $ext = strtolower($pathParts["extension"]);
            $imtMap = wget('Resources:IMTMap');

            if (isset($imtMap[$ext])) {
                return $imtMap[$ext];
            }
        } else {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $imt = finfo_file($finfo, $path);
            finfo_close($finfo);
            
            return $imt;
        }

        return false;
    }

}
