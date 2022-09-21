<?php
	ini_set('display_errors', 1);
    error_reporting (E_ALL);
	session_start();
	
    if (version_compare(phpversion(), '7.1.0', '<') == true) { die ('PHP7.1 Only'); }
    const DIRSEP = DIRECTORY_SEPARATOR;
	$site_path = realpath(dirname(__FILE__) . DIRSEP . '..' . DIRSEP) . DIRSEP;
	
	define("site_path", $site_path);
	
	require_once 'application/bootstrap.php';
	