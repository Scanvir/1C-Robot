<?php
    error_reporting (E_ALL);

    if (version_compare(phpversion(), '7.1.0', '<') == true) { die ('PHP7.1 Only'); }

	ini_set('display_errors', 1);
    const DIRSEP = DIRECTORY_SEPARATOR;

    $site_path = realpath(dirname(__FILE__) . DIRSEP . '..' . DIRSEP) . DIRSEP;
    define("site_path", $site_path);

    require_once 'application/bootstrap.php';