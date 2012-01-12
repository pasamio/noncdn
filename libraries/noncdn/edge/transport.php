<?php
/**
 * @package     NonCDN
 * @subpackage  Edge
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

/**
 * Edge Transport class
 *
 * @package     NonCDN
 * @subpackage  Edge
 * @since       1.0
 */
class Edge_Transport
{
	/** @var    Edge_Factory  An edge factory to construct objects.
	 *  @since  1.0
	 */
	protected $factory;
	
	/** @var     Edge_Database  An edge database to retrieve and store information.
	 *  @since   1.0
	 */
	protected $db;
	
	/** @var    array  An array of master servers.
	 *  @since  1.0
	 */
	protected $masterServers;
	
	/** 
	 * Constructor
	 *
	 * @param  Edge_Factory  $factory        An edge factory for building various objects.
	 * @param  array         $masterServers  An array of master servers to attempt to connect.
	 * @param  string        $cacheDir       The cache directory for this edge node.
	 *
	 * @since  1.0
	 */
	public function __construct($factory, $masterServers, $cacheDir)
	{
		// save our args
		$this->factory = $factory;
		$this->masterServers = $masterServers;
		$this->cacheDir = $cacheDir;
		
		// build some classes
		$this->db = $this->factory->buildEdgeDatabase();
		$this->masterClient = $this->factory->buildMasterClient();
	}

	/**
	 * Deliver a file to a client
	 *
	 * @param   string  $container  The container from which to deliver the file.
	 * @param   string  $path       The path to the file in the container.
	 *
	 * @return  void  This function handles the final aspect of the request.
	 *
	 * @since   1.0
	 */
	public function deliver($container, $path)
	{
		// grab the filename and clean it
		$filename = end($path);
		$filename = Route::cleanPath($filename);
		
		// clean each part of the path removing any '..' that might be there, filter out empty entries
		// and then implode it back into a path
		$path = implode('/', array_filter(array_map(Array('NonCDN\Route', 'cleanPath'), $path), 'strlen'));

		// First step: work out if we have this file locally then potentially deliver it
		$contentId = $this->db->findFileContentId($container, $path);
		if(!empty($contentId))
		{
			// so we had the file locally! sweet...
			$this->deliverFileFromPath($this->buildPathFromContentId($contentId));
			exit(0);
		}
		
		// Step 2: ask the master server for this file
		$contentId = $this->masterClient->getContentId($container, $path);
		
		if(!empty($contentId))
		{
			// look if we have a copy of this file locally already
			$path = $this->buildPathFromContentId($contentId))
		}
		
		// Step 3: it doesn't exist...soz!
		RequestSupport::terminate(404, 'File Not Found');
	}
	
	/**
	 * Stream a file both to the local disk and to the end user
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function streamFile()
	{
		// :(
	}
	
	public function buildPathFromContentId($contentId)
	{
		$
	}
	
	/**
	 * Deliver a file from a local path
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function deliverFileFromPath($filePath)
	{
		// TODO: used the cached mime type from the master node
		header('Content-type: ' . mime_content_type($filePath));
		readfile($filePath);
	}
}