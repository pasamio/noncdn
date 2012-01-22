<?php
/**
 * @package     NonCDN
 * @subpackage  Edge
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

/**
 * Edge Factory class
 *
 * @package     NonCDN
 * @subpackage  Edge
 * @since       1.0
 */
class Edge_Factory extends Factory
{
	/**
	 * Build edge transport class to handle transferring a file.
	 *
	 * @return  Edge_Transport  An edge transport to use configured with this factory, master servers and cache directory.
	 *
	 * @since   1.0
	 */
	public function buildEdgeTransport()
	{
		return new Edge_Transport($this, $this->configuration->getMasterServers(), $this->configuration->getCacheDir());
	}

	/**
	 * Build an authorisor class
	 *
	 * @return  Authorisor  The authorisation class to use.
	 *
	 * @since   1.0
	 */
	public function buildAuthorisor()
	{
		$authoriserClass = $this->configuration->getAuthorisorClass();
		return new $authoriserClass($this->configuration->getAuthServers());
	}

	/**
	 * Build an edge database handler.
	 *
	 * @return  Edge_Database  The edge database handler.
	 *
	 * @since   1.0
	 */
	public function buildEdgeDatabase()
	{
		return new Edge_Database($this->buildDatabaseConnector());
	}

	/**
	 * Build a database connector.
	 *
	 * @return  JDatabase  A database connector to the edge DB
	 *
	 * @since   1.0
	 */
	public function buildDatabaseConnector()
	{
		$db = \JDatabase::getInstance(
			array(
				'driver' => 'pdo',
				'database' => $this->configuration->getEdgeDBPath()
			)
		);
		return $db;
	}

	/**
	 * Build a client for communicating with a master node.
	 *
	 * @return  Master_Client  A master client.
	 *
	 * @since   1.0
	 */
	public function buildMasterClient()
	{
		return new Master_Client($this, $this->configuration->getMasterServers());
	}
}
