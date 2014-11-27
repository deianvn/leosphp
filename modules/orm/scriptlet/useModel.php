<?php

$modelResourceInfo = $this->getApplication()->locateResource($name, '.model');

if ($modelResourceInfo === false) {
    throw new \ls\orm\ModelNotFoundException('Could no locate model: ' . $name);
}

require_once $modelResourceInfo->getPath();

$modelFactoryResourceInfo = $this->getApplication()->locateResource($name . '.factory', '.model');

if ($modelFactoryResourceInfo === false) {
    throw new \ls\orm\ModelNotFoundException('Could no locate factory for model: ' . $name);
}

require_once $modelFactoryResourceInfo->getPath();

$modelManagerResourceInfo = $this->getApplication()->locateResource($name . '.manager', '.model');

if ($modelManagerResourceInfo === false) {
    throw new \ls\orm\ModelNotFoundException('Could no locate manager for model: ' . $name);
}

require_once $modelManagerResourceInfo->getPath();
