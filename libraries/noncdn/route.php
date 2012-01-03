<?php
/**
 * @package     NonCDN
 * @subpackage  Route
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

/**
 * Route support class
 *
 * @package     NonCDN
 * @subpackage  Route
 * @since       1.0
 */
class Route
{
	/**
	 * Parse the arguments in the request.
	 *
	 * @return  array  The individual route segments.
	 *
	 * @since   1.0
	 */
	static public function parseArguments()
	{
		static $route = null;
		if (is_null($route))
		{
			// grab our path and trim out any final slashes (shouldn't be but doesn't hurt to check)
			$self = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));

			// check quickly in case our last part contains a .php and lop that off
			if (preg_match('/.php$/', end($self)))
			{
				array_pop($self);
			}
			$uri_parts = explode('?', $_SERVER['REQUEST_URI']);
			// grab the uri we came in on, again stripping out any slashes
			$uri = explode('/', trim($uri_parts[0], '/'));

			// basically we should be the offset of our parent
			$route = array_slice($uri, count($self));
		}
		return $route;
	}

	/**
	 * Determine the base path for the script.
	 *
	 * @return  string  Base path for the script (URI)
	 *
	 * @since   1.0
	 */
	static public function getBasePath()
	{
		static $base = null;
		if (is_null($base))
		{
			$self = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));
			if (preg_match('/.php$/', end($self)))
			{
				array_pop($self);
			}
			$base = 'http://' . $_SERVER['HTTP_HOST'] . '/' . implode('/', $self);
		}
		return $base;
	}

	/**
	 * Clean up a path to remove any malicious path traversal stuff.
	 *
	 * @param   string  $input  The path to clean.
	 *
	 * @return  string  The cleaned path.
	 *
	 * @since   1.0
	 */
	static public function cleanPath($input)
	{
		$count = 0;
		do
		{
			$start = $input;
			$input = str_replace('..', '', $input);
			$count++;
			// prevent malicious infinite loop
			if ($count > 5)
			{
				RequestSupport::terminate(500, 'Invalid Request');
			}
		}
		while($start != $input);
		$filter = new \JFilterInput;
		return $filter->clean($input, 'CMD');
	}
}
