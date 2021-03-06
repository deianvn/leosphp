<?php

namespace ls\internal;

define('BASE_DIR', getcwd() . DIRECTORY_SEPARATOR);
set_include_path('.' . PATH_SEPARATOR . BASE_DIR);

$cache = array();
$loc = array();

require BASE_DIR . 'internal/LS.php';
require BASE_DIR . 'internal/Container.php';
require BASE_DIR . 'internal/ResourceContainer.php';
require BASE_DIR . 'internal/Application.php';
require BASE_DIR . 'internal/Module.php';
require BASE_DIR . 'internal/Resource.php';
require BASE_DIR . 'internal/ResourceInfo.php';
require BASE_DIR . 'internal/Operation.php';
require BASE_DIR . 'internal/Servlet.php';
require BASE_DIR . 'internal/Page.php';
require BASE_DIR . 'internal/ResourceNotFoundException.php';
require BASE_DIR . 'internal/IllegalRouteException.php';
require BASE_DIR . 'internal/MalformedRequestException.php';
require BASE_DIR . 'internal/Route.php';
require BASE_DIR . 'internal/Request.php';
require BASE_DIR . 'internal/Router.php';
require BASE_DIR . 'internal/Action.php';
require BASE_DIR . 'internal/Command.php';

function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir . "/" . $object) == "dir")
                    rrmdir($dir . "/" . $object);
                else
                    unlink($dir . "/" . $object);
            }
        }
        reset($objects);
        rmdir($dir); 
    }
}
