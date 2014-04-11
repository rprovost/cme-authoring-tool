<?php
require_once '../config.php';
include_once 'config/common.php';

// Application Information
define('APP_NAME','CME Bundle Creator');
define('APP_URL','http://www.mysonystuff.com/authoring');
define('APP_CREATED',date('Y-m-d'));
define('APP_VERSION','0.9.5');
define('APP_MODIFIED','2010-12-13');

// Create a session to follow the user
if ( !isset($_SESSION['bundler']) && (!isset($_REQUEST['action']) || ($_REQUEST['action'] == 'home'))) {
	$_SESSION['bundler']['manifestid']	= rand(1000000000,9999999999);
	$_SESSION['bundler']['password']	= generatePassword(5,4);
} else if ( !isset($_SESSION['bundler']) && !isset($_REQUEST['msg']) ) {
	header('location: /authoring/?msg=100');
}

// Application Settings
define('MANIFESTID',$_SESSION['bundler']['manifestid']);
define('PASSWORD',$_SESSION['bundler']['password']);
define('APP_ABSOLUTE_ROOT', $_SERVER['DOCUMENT_ROOT'].'/authoring');
define('APP_CLASS_ROOT', $_SERVER['DOCUMENT_ROOT'].'/authoring/classes');

// Need to do this right!
mysql_connect($dbhost,$dbuser,$dbpwd);
$DB = mysql_select_db($dbname);

// This will allow plugins to resume/initialize properly
ini_set('unserialize_callback_func','unserialize_callback');
include_once APP_CLASS_ROOT.'/PluginHelper.class.php';
