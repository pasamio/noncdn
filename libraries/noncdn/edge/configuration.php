<?php
/**
 * @package     NonCDN
 * @subpackage  Edge
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

/**
 * Edge Configuration class
 *
 * @package     NonCDN
 * @subpackage  Edge
 * @since       1.0
 */
class Edge_Configuration extends Configuration
{
	/**
	 * Get edge secret
	 * Note: For edge servers the param is ignored as edges only have a single secret, their secret.
	 *
	 * @param  $edge  integer  The edge whose secret to retrieve. Ignored as "edge" applications have only one secret.
	 *
	 * @throws  InvalidConfiguration
	 * @return  string  The edge secret.
	 *
	 * @since  1.0
	 */ 
	public function getEdgeSecret($edge=null)
	{
		if(isset($this->configuration->edge_secret))
		{
			return $this->configuration->edge_secret;
		}
		throw new InvalidConfiguration('Missing Edge Secret');
	}

	/**
	 * Return the edge identifier
	 *
	 * @param  $edge  integer  The edge identifier for this node; used for validation
	 *
	 * @return  boolean  
	 * @throws  InvalidConfiguration  If the edge identifier is not set
	 */
	public function getEdgeIdentifier($edge)
	{
		if(isset($this->configuration->edge_id))
		{
			return ($this->configuration->edge_id === $edge);
		}
		throw new InvalidConfiguration('Missing Edge Identifier');
	}
	
	/**
	 * Return a a list of master servers for content retrieval
	 *
	 * @return  array  Array of URI's for master nodes (randomly picked)
	 *
	 * @since   1.0
	 */
	public function getMasterServers()
	{
		return $this->configuration->master_servers;	
	}
	
	/**
	 * Return a list of authentication and authorisation servers
	 *
	 * @return  array  Array of URI's for auth servers
	 *
	 * @since  1.0
	 */
	public function getAuthServers()
	{
		return $this->configuration->auth_servers;
	}
	
	/**
	 * Return the class name of the authorisor.
	 * This will use a configured authoriser if one is set or has a default setting.
	 *
	 * @return  string  name of the authoriser class including any namespace.
	 *
	 * @since  1.0
	 */
	public function getAuthorisorClass()
	{
		if(isset($this->configuration->authoriser))
		{
			if(class_exists($this->configuration->authoriser))
			{
				return $this->configuration->authoriser;
			}
		}
		return 'NonCDN\Edge_Authorisor'; // default authorisor
	}
	
	/**
	 * Get the path to the edge database.
	 *
	 * @return  string  The path to the edge database.
	 *
	 * @since   1.0
	 */ 
	public function getEdgeDBPath()
	{
		if (!isset($this->configuration->edgedb))
		{
			throw new Exception('Missing edge database in configuration file.');
		}
		return $this->configuration->edgedb;
	}
	
	/**
	 * Get the edge cache directory
	 *
	 * @return  string  The path to the cache directory.
	 *
	 * @throws  Exception  If the cache dir is unset, missing or unwritable
	 *
	 * @since   1.0
	 */
	public function getCacheDir()
	{
		if (!isset($this->configuration->cachedir))
		{
			throw new Exception('Cache dir not set');
		}
		if (!file_exists($this->configuration->cachedir))
		{
			throw new Exception('Invalid or missing cache dir specified');
		}
		if (!is_writable($this->configuration->cachedir))
		{
			throw new Exception('Invalid cache dir specified');
		}
		return $this->configuration->cachedir;
	}
}
