<?php
/**
 * @package     NonCDN
 * @subpackage  Master
 * @copyright   Copyright (C) 2012 Sam Moffatt
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

/**
 * Master Configuration Object
 *
 * @package     NonCDN
 * @subpackage  Master
 * @since       1.0
 */
class Master_Configuration extends Configuration
{
	/**
	 * Return the selected authenticator (or the default)
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getAuthenticator()
	{
		if (isset($this->configuration->authenticator))
		{
			return $this->configuration->authenticator;
		}

		// fallback authenticator class
		return '\NonCDN\Authenticator_Basic';
	}

	/**
	 * Get the realm name for displaying in the authentication challenge.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getRealm()
	{
		if (isset($this->configuration->auth_realm))
		{
			return $this->configuration->auth_realm;
		}

		// fallback realm
		return 'NonCDN Authentication';
	}

	/**
	 * Get an array of all of the edge nodes.
	 *
	 * @return  array  An array of edge node URI's
	 *
	 * @since   1.0
	 */
	public function getEdgeMap()
	{
		if (isset($this->configuration->edge_map))
		{
			return $this->configuration->edge_map;
		}
		return array();
	}
	
	public function getDataStore()
	{
		if (!isset($this->configuration->data_store))
		{
			throw new Exception('Data store missing from master configuration file');
		}
		return $this->configuration->data_store;
	}
	
	/**
	 * Get the path to the master database.
	 *
	 * @return  string  The path to the master database.
	 *
	 * @since   1.0
	 */ 
	public function getMasterDBPath()
	{
		if (!isset($this->configuration->masterdb))
		{
			throw new Exception('Missing master database in configuration file.');
		}
		return $this->configuration->masterdb;
	}
}
