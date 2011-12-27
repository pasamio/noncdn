<?php

namespace NonCDN;

class Master_Controller_Content extends Master_Controller_Base
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
		
		// since we know who the user is we now can deliver them the file
		$this->deliverFile($username, $args);
	}
}