<?php
// Version
define('VERSION', '2.3.0.2');

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: ../install/index.php');
	exit;
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');
						
// Front Controller
$controller = new Front($registry);

// Router
$action = new Action('tool/import_yml/cron');

// Dispatch
$controller->dispatch($action, new Action('error/not_found'));
?>
