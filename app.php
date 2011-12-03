<?php
namespace NonCDN;

defined('NONCDN') or die();

// Load the Joomla! Platform
define('JPATH_PLATFORM', __DIR__.'/libraries/joomla-platform');
define('JPATH_ROOT', __DIR__);
define('DS', DIRECTORY_SEPARATOR);

// Load and setup the Joomla! class loader
require JPATH_PLATFORM.'/libraries/loader.php';
\JLoader::setup();

// Load the PSR-0 Class Loader and setup NonCDN namespace
require __DIR__.'/libraries/psrloader.php';
$loader = new \SplClassLoader('NonCDN', __DIR__.'/libraries');
$loader->register();

// Workout what we're doing
$route = Route::parseArguments();
if(!count($route))
{
	RequestSupport::terminate(500, 'Invalid Request');
}

// Borrow some Joomla!
jimport('platform');
jimport('joomla.base.object');
jimport('joomla.filter.filterinput');
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

