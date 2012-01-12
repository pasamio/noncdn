<?php
/**
 * @package     NonCDN
 * @subpackage  Edge
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

/**
 * Edge Master Client class
 * This class handles communicating with a master node.
 *
 * @package     NonCDN
 * @subpackage  Edge
 * @since       1.0
 */
class Edge_MasterClient
{
	/** @var    $masterServers  An array of master servers to attempt to communicate with.
	 *  @since  1.0
	 */	
	protected $masterServers;
	
	/**
	 * Constructor
	 *
	 * @param  array  $masterServers  An array of master
	 *
	 * @since  1.0
	 */
	public function __construct($masterServers)
	{
		$this->masterServers = $masterServers;
		$this->server = $this->masterServers[0];
	}
	
	/**
	 * Get the content ID for a given container and path.
	 *
	 * @param   string  $container  The container.
	 * @param   string  $path       The path.
	 *
	 * @return  integer  The content ID for this file.
	 *
	 * @since   1.0
	 */
	public function getContentId($container, $path)
	{
		var_dump($this->server);
	}
}