<?php

namespace ls\core;

class LSFormParam {

    public $type;
    public $name;
    public $scope;

    public static function create($type, $name, $scope) {
        $formParam = new LSFormParam();
        $formParam->type = $type;
        $formParam->name = $name;
        $formParam->scope = $scope;

        return $formParam;
    }

}
