<?php
require_once(__DIR__.'/util/general.php');

$GLOBALS['db']['host'] = 'localhost';
$GLOBALS['db']['user'] = 'root';
$GLOBALS['db']['password'] = '';
$GLOBALS['db']['database'] = 'tatoo_webservice';
$GLOBALS['db']['charset'] = 'utf8';

$GLOBALS['debug'] = false;
$GLOBALS['config']['baseUrl'] = 'http://localhost';

//autoloader
spl_autoload_register('classLoad');

if (file_exists(__DIR__.'/config.php'))
	require_once(__DIR__.'/config.php');

require_once(__DIR__.'/util/mysql.php');