<?php
/**
 * @package     NonCDN
 * @subpackage  Master
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

/**
 * Master Client class
 * This class handles communicating with a master node.
 *
 * @package     NonCDN
 * @subpackage  Master
 * @since       1.0
 */
class Master_Client
{
	/** @var    $masterServers  An array of master servers to attempt to communicate with.
	 *  @since  1.0
	 */	
	protected $masterServers;
	
	/** @var    $factory  A copy of the factory.
	 *  @since  1.0
	 */
	protected $factory;
	
	/**
	 * Constructor
	 *
	 * @param  array  $masterServers  An array of master
	 *
	 * @since  1.0
	 */
	public function __construct($factory, $masterServers)
	{
		$this->factory = $factory;
		$this->masterServers = $masterServers;
		$this->server = $this->masterServers[0];
	}
	
	/**
	 * Get the content ID for a given container and path.
	 *
	 * @param   string  $container  The container.
	 * @param   string  $path       The path.
	 *
	 * @return  integer  The content ID for this file or false if no content ID exists.
	 *
	 * @since   1.0
	 */
	public function getContentId($container, $path)
	{
	    $uri  = $this->server . '/content/get_content_id?';
	    $uri .= http_build_query(array('container'=>$container, 'path'=>$path));
        $data = json_decode(file_get_contents($uri));
        
        return isset($data->file_unique_id) ? $data->file_unique_id : false;
    }
    
    /**
     * Gets a file handle for a particular content item from the master server.
     *
     * @param   string  $contentId  The content ID of the file to retrieve.
     *
     * @return  handle  Open file handle to the master server.
     *
     * @since   1.0
     */
    public function getContentHandle($contentId)
    {
        $uri  = $this->server . '/content/get_content?file_unique_id=' . (int) $contentId;

        return fopen($uri, 'r');
    }
    
    /**
     * Extra file metadata from the HTTP headers of a stream
     *
     * @param   handle  $stream  A file handle pointed to a HTTP server.
     *
     * @return  File  A file object with metadata filled in. Check file_id to see if this was successful.
     *
     * @since   1.0
     */
    public function extractFileMetaDataFromStream($stream)
    {
        $data = stream_get_meta_data($stream);
        $file = $this->factory->buildFile();
        
        if (isset($data['wrapper_data']))
        {
            foreach($data['wrapper_data'] as $datum)
            {
                if (strpos($datum, 'X-NonCDN-FileMeta: ') !== false)
                {
                    $record = str_replace('X-NonCDN-FileMeta: ', '', $datum);
                    $record = json_decode($record);
                    $file->bind($record);
                    break;
                }
            }
        }

        return $file;
    }
}
