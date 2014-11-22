<?php

set_include_path('.' . PATH_SEPARATOR . INCLUDE_DIR);
$lswallet = array();
$lssessionwallet = array();
$lscontextstack = array();
$lscache = array();
$lsloc = array();

require INCLUDE_DIR . '/core/utils.php';
require INCLUDE_DIR . '/core/public.php';
require INCLUDE_DIR . '/config/config.php';
require INCLUDE_DIR . '/config/routes.php';
require INCLUDE_DIR . '/core/LSCommon.php';
require INCLUDE_DIR . '/core/LSApplication.php';
require INCLUDE_DIR . '/core/LSContext.php';
require INCLUDE_DIR . '/core/LSLogger.php';
require INCLUDE_DIR . '/core/LSHttp.php';
require INCLUDE_DIR . '/core/LSRoute.php';
require INCLUDE_DIR . '/core/LSRouter.php';
require INCLUDE_DIR . '/core/LSLocMgr.php';
require INCLUDE_DIR . '/core/LSWorkflowMgr.php';
require INCLUDE_DIR . '/core/LSActionMgr.php';
require INCLUDE_DIR . '/core/LSResourceMgr.php';
require INCLUDE_DIR . '/core/LSFormParam.php';
require INCLUDE_DIR . '/core/LSConverter.php';
require INCLUDE_DIR . '/core/LSBaseAction.php';
require INCLUDE_DIR . '/core/LSAction.php';
require INCLUDE_DIR . '/core/LSPage.php';
