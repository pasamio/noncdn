<?php
/**
 * @package     NonCDN
 * @subpackage  Manager
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('NONCDN') or die();

/**
 * Manager Base Controller.
 * Base controller for manager application to decorate JApplicationCLI.
 *
 * @package     NonCDN
 * @subpackage  Manager.Controller
 *
 * @since       1.0
 */
class Controller
{
	/**
	 * @var    JApplicationCLI  The instance of the application
	 * @since  1.0
	 */
	private $_app;

	/**
	 * Constructor
	 *
	 * @param   JApplicationCLI  &$app  A reference to the JApplicationCLI running this instance.
	 *
	 * @since   1.0
	 */
	public function __construct(&$app)
	{
		$this->_app = $app;
	}

	/**
	 * Magic Get!
	 *
	 * @param   string  $name  The name of the variable being accessed.
	 *
	 * @return  mixed  Contents of the variable from the application instance
	 *
	 * @since   1.0
	 */
	public function __get($name)
	{
		if (isset($this->_app->$name))
		{
			return $this->_app->$name;
		}
		return null;
	}

	/**
	 * Magic Call!
	 *
	 * @param   string  $name  The name of the function being called.
	 * @param   array   $args  The argument array for this call.
	 *
	 * @return  mixed  The result of the function call.
	 *
	 * @since   1.0
	 */
	public function __call($name, $args)
	{
		if (method_exists($this->_app, $name))
		{
			return call_user_func_array(array($this->_app, $name), $args);
		}
		return null;
	}
}
