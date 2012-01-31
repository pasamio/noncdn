<?php
namespace NonCDN;

defined('NONCDN') or die();

// Load the Joomla! Platform
define('JPATH_PLATFORM', __DIR__.'/libraries/joomla-platform/libraries');
define('JPATH_ROOT', __DIR__);
define('JPATH_BASE', __DIR__);
define('DS', DIRECTORY_SEPARATOR);

// Load and setup the Joomla! class loader plus backports and eBay content branch
require __DIR__.'/libraries/import.php';

// Load the PSR-0 Class Loader and setup PEAR namespace
require __DIR__.'/libraries/psrloader.php';
$loader = new \SplClassLoader;
$loader->add('PEAR', __DIR__.'/libraries');
$loader->register();

// Load the NonCDN class loader, a derivative of PSR-0
require __DIR__.'/libraries/noncdnloader.php';
$noncdnloader = new \NonCDNClassLoader('NonCDN', __DIR__.'/libraries');
$noncdnloader->register();

// Workout what we're doing
$route = Route::parseArguments();
if(!count($route))
{
	RequestSupport::terminate(500, 'Invalid Request');
}

// Borrow some Joomla!
jimport('joomla.base.object');
$inputfilter = new \JFilterInput();
$controller = $inputfilter->clean(array_shift($route), 'CMD');

// grab the controller and have fun
if(empty($controller))
{
	RequestSupport::terminate(500, 'Invalid Controller');
}

// Grab an instance of the configuration
if(!class_exists('\NonCDNConfiguration'))
{
	RequestSupport::terminate(500, 'Invalid Configuration');
}
$config = new \NonCDNConfiguration();	

// Create a logger
jimport('joomla.log.log');
jimport('joomla.factory');
\JLog::addLogger(Array('text_file_path'=>__DIR__.'/logs'));

// Grab an instance of the factory
$factory = $config->appname.'_Factory';
if(!class_exists($factory))
{
	RequestSupport::terminate(500, 'Missing Factory');
}
$factory = new $factory($config);	

// Grab an instance of our controller
$controllerClass = $config->appname.'_Controller_'. $controller;
if(!class_exists($controllerClass))
{	
	RequestSupport::terminate(500, 'Controller Missing Programmer Alert');
}
$controllerInstance = new $controllerClass($factory);

// look for an execute method and run with it
if(!method_exists($controllerInstance, 'execute'))
{
	RequestSupport::terminate(500, 'Programmer Controller Mismatch');
}
$controllerInstance->execute($route);	

