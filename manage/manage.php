#!/usr/bin/env php
<?php
/**
 * @package     NonCDN
 * @subpackage  Manager
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
define('_JEXEC', 1);
define('NONCDN', 1);

require_once '../cli.php';

jimport('joomla.application.cli');

/**
 * CLI Manager Class
 *
 * @package     NonCDN
 * @subpackage  Manager
 *
 * @since       1.0
 */
class NonCDN_Manager extends JCli
{
	/**
	 * Execute this CLI instance
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function execute()
	{
		$validApps = array('master', 'auth', 'edge');

		if (!count($this->input->args))
		{
			$this->out('Usage: ' . $this->input->executable . ' [app] [command] [options]');
			exit(1);
		}

		$app = $this->input->args[0];

		if (!in_array($app, $validApps))
		{
			$this->out('Invalid app specified');
			exit(1);
		}

		$controllerFile = __DIR__ . "/apps/$app.php";
		if (!file_exists($controllerFile))
		{
			$this->out('Error loading app');
			exit(2);
		}
		require $controllerFile;

		$factoryFile = __DIR__ . "/factories/$app.php";
		if (!file_exists($factoryFile))
		{
			$this->out('Error loading app factory');
			exit(2);
		}

		require $factoryFile;

		// set up the app controller and load it
		$controllerName = $app . 'Controller';
		$factoryName = $app . 'Factory';
		$controller = new $controllerName($this, new $factoryName($this));
		$controller->execute();
	}

	/**
	 * Method to load a PHP configuration class file based on convention and return the instantiated data object.  You
	 * will extend this method in child classes to provide configuration data from whatever data source is relevant
	 * for your specific application.
	 *
	 * @param   string  $file   The path and filename of the configuration file. If not provided, configuration.php
	 *                          in the relative app directories will be used.
	 * @param   string  $class  The class name to instantiate.
	 *
	 * @return  mixed   Either an array or object to be loaded into the configuration object.
	 *
	 * @since   1.0
	 */
	public function fetchConfigurationData($file = '', $class = 'NonCDNConfiguration')
	{
		if (empty($file))
		{
			$configurationFile = $this->input->get('configuration');

			if (!empty($configurationFile))
			{
				if (file_exists($configurationFile))
				{
					$file = $configurationFile;
				}
				else
				{
					throw new Exception('Configuration file specified missing!');
				}
			}
			else
			{
				// if a configuration file isn't specified, configure based on default Git repo layout
				$root = dirname(__DIR__);

				if (isset($this->input->args[0]) && strlen($this->input->args[0]))
				{
					$testPath = $root . '/' . $this->input->args[0] . '/configuration.php';
					if (file_exists($testPath))
					{
						$file = $testPath;
					}
				}
			}
		}
		return parent::fetchConfigurationData($file, $class);
	}
}

try
{
	// execute this application
	JCli::getInstance('NonCDN_Manager')->execute();
}
catch (Exception $e)
{
	echo "Fatal error: " . $e->getMessage() . "\n";
	exit(1);
}
