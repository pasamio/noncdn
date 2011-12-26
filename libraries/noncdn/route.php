<?php

namespace NonCDN;

class Route
{
	static public function parseArguments()
	{
		static $route = null;
		if(is_null($route))
		{
			// grab our path and trim out any final slashes (shouldn't be but doesn't hurt to check)
			$self = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));

			// check quickly in case our last part contains a .php and lop that off
			if(preg_match('/.php$/', end($self)))
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

	static public function getBasePath()
	{
		static $base = null;
		if(is_null($base))
		{
			$self = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));
			if(preg_match('/.php$/', end($self)))
			{
				array_pop($self);
			}
			$base = 'http://'.$_SERVER['HTTP_HOST'].'/'.implode('/', $self);
		}
		return $base;
	}
	
	static public function cleanPath($input)
	{
		$count = 0;
		do
		{
			$start = $input;
			$input = str_replace('..', '', $input);
			$count++;
			if($count > 5) { // prevent malicious infinite loop
				RequestSupport::terminate(500, 'Invalid Request');
			}
		} while($start != $input);	
		$filter = new \JFilterInput;
		return $filter->clean($input, 'CMD');
	}	
}
