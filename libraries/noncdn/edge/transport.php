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
	
	/** @var     string  The cache temporary storage directory.
	 *  @since   1.0
	 */
	protected $cacheDir;
	
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
		$path = implode('/', array_filter(array_map(array('NonCDN\Route', 'cleanPath'), $path), 'strlen'));

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
			$localpath = $this->buildPathFromContentId($contentId);
			
			if (file_exists($localpath))
			{
				// link this file and this content ID together
				$this->db->assignFileContentId($contentId, $container, $path);
				// then send it
				$this->deliverFileFromPath($localpath);
				exit(0);
			}
			else
			{
				$this->streamFile($contentId, $container, $path);
				exit(0);
			}
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
	public function streamFile($contentId, $container, $path)
	{
		// use streams to download the file and output to the client simultaneously
		$fh = $this->masterClient->getContentHandle($contentId);

		if (!$fh)
		{
			RequestSupport::terminate(500, 'File access error');
		}
		
		$params = array('outFile'=>$this->buildPathFromContentId($contentId));
		
		// Register the stream filter
		stream_filter_register('noncdn.duplicate', 'NonCDN\Stream_Filter_Duplicate');

		stream_filter_append($fh, 'noncdn.duplicate', STREAM_FILTER_READ, $params);
		
		$file = $this->masterClient->extractFileMetaDataFromStream($fh);
		
		$file->create();
		
		if (strlen($file->file_mime))
		{
			header('Content-type: ' . $file->file_mime);
		}

		if (is_integer($file->file_size) && !empty($file->file_size))
		{
			header('Content-length: ' . $file->file_size);			
		}
		
		fpassthru($fh);
		
		// last step is to assign this file content ID to the file we just streamed
		$this->db->assignFileContentId($contentId, $container, $path);
	}
	
	/**
	 * Build a path to the cache directory based on the content ID
	 *
	 * @param   integer  $contentId  The content ID to retrieve.
	 *
	 * @return  string  The path to the file.
	 *
	 * @since   1.0
	 */
	public function buildPathFromContentId($contentId)
	{
		return $this->cacheDir . '/' . $contentId;
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
		//header('Content-Disposition: attachment;filename=' . basename($filePath));
		// TODO: used the cached mime type from the master node
		header('Content-type: ' . mime_content_type($filePath));
		readfile($filePath);
	}
}
