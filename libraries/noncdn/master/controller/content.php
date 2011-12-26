<?php

namespace NonCDN;

class Master_Controller_Content extends BaseController
{
	public function get_content_id($args)
	{
		$filter = new \JFilterInput;
		$container = $filter->clean($_GET['container'], 'CMD');
		$path = $filter->clean($_GET['path'], 'PATH');
		$localPath = JPATH_ROOT.'/data/'. $container.'/'.$path;	
		$this->outputResponse(Array('file_unique_id'=>fileinode($localPath)));
	}
	
	public function get_content($args)
	{
		$filter = new \JFilterInput;
		$contentId = $filter->clean($_GET['file_unique_id'], 'CMD');
		
		
	}
	
	public function file($args)
	{
		if (!count($args) || count($args) < 2)
		{
			RequestSupport::terminate(500, 'Missing container and path information');
		}
		
		// start the session and authenticate the end user
		session_start();
		if (!isset($_SESSION['username']) || empty($_SESSION['username']))
		{
			$authenticator = $this->factory->buildAuthenticator();
			$result = $authenticator->authenticate();
			if ($result)
			{
				$_SESSION['username'] = $result;	
			}
			else
			{
				RequestSupport::terminate(500, 'Unidentified User');
			}
		}
		
		$username = $_SESSION['username'];
		
		$path = $args; // use a copy
		
		$filter = new \JFilterInput;
		$container = $filter->clean(array_shift($path), 'CMD');
		$path = $filter->clean(implode('/', $path), 'PATH');		
		
		$authoriser = $this->factory->buildAuthoriser();
		if (!$authoriser->check_user_access($container, $username))
		{
			RequestSupport::terminate(403, 'Access Denied');
		}
		
		echo ':D';
		
	}
}