<?php
namespace NonCDN;

defined('NONCDN') or die();

// Load the Joomla! Platform
define('JPATH_PLATFORM', __DIR__.'/libraries/joomla-platform/libraries');
define('JPATH_ROOT', __DIR__);
define('DS', DIRECTORY_SEPARATOR);

// Load and setup the Joomla! class loader
require JPATH_PLATFORM.'/import.php';
require __DIR__.'/libraries/import.php';

// Load the PSR-0 Class Loader and setup NonCDN namespace
require __DIR__.'/libraries/psrloader.php';
$loader = new \SplClassLoader('NonCDN', __DIR__.'/libraries');
$loader->register();
