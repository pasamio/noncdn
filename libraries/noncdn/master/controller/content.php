<?php
/**
 * @package     NonCDN
 * @subpackage  Manager
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

/**
 * Master content controller
 *
 * @package     NonCDN
 * @subpackage  Container
 * @since       1.0
 */
class Master_Controller_Content extends Master_Controller_Base
{
	/**
	 * Get content ID for a given container and path
	 *
	 * @param   array  $args  args
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function get_content_id($args)
	{
		$filter = new \JFilterInput;
		$container_name = $filter->clean($_GET['container'], 'CMD');
		$path = $filter->clean($_GET['path'], 'PATH');
		
		$container = $this->factory->buildContainer();
		$container->loadContainerByName($container_name);
		
		if (empty($container->container_id))
		{
			throw new Exception('Invalid Container');
		}

		$file = $container->getFileByPath($path);
		if (empty($file->file_id))
		{
			throw new Exception('Invalid File');
		}
		$this->outputResponse(Array('file_unique_id'=>$file->file_id));
	}
	
	/**
	 * Get a content item for a matching file unique ID
	 *
	 * @params   array  $args  The args for this request
	 *
	 * @return   void
	 *
	 * @since    1.0
	 */
	public function get_content($args)
	{
		$filter = new \JFilterInput;
		$contentId = $filter->clean($_GET['file_unique_id'], 'CMD');
		
		$file = $this->factory->buildFile();
		$file->load($contentId);
		$path = $file->getFilePath();
		
		header('X-NonCDN-FileMeta: ' . $file);
		
		readfile($path);
	}
	
	/**
	 * Handle authenticating a user and then providing them the file.
	 *
	 * @params   array  $args  The args for the request.
	 *
	 * @return   void
	 *
	 * @since    1.0
	 */
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