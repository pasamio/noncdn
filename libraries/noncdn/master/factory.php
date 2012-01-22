<?php
/**
 * @package     NonCDN
 * @subpackage  Master
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

/**
 * Master Factory
 *
 * @package     NonCDN
 * @subpackage  Master
 * @since       1.0
 */
class Master_Factory extends Factory
{
	/**
	 * Build an authenticator class
	 *
	 * @return  Authenticator
	 *
	 * @since   1.0
	 */
	public function buildAuthenticator()
	{
		$authenticatorClass = $this->configuration->getAuthenticator();
		return new $authenticatorClass($this->configuration, $this);
	}

	/**
	 * Build an edge router
	 *
	 * @return  Master_EdgeRouter  The edge router.
	 *
	 * @since   1.0
	 */
	public function buildEdgeRouter()
	{
		return new Master_EdgeRouter($this->configuration, $this);
	}

	/**
	 * Build a database connector
	 *
	 * @return  JDatabase  A connected database connection
	 *
	 * @since   1.0
	 */
	public function buildDatabaseConnector()
	{
		$db = \JDatabase::getInstance(
			array(
				'driver' => 'pdo',
				'database' => $this->configuration->getMasterDBPath()
			)
		);
		return $db;
	}

	/**
	 * Build a file instance and set the base dir for the master node.
	 *
	 * @param   JDatabase  $db  The database object
	 *
	 * @return  File  A new file object
	 *
	 * @since   1.0
	 */
	public function buildFile($db = null)
	{
		$file = parent::buildFile($db);
		$file->setBaseDir($this->configuration->getDataStore());
		return $file;
	}
}
