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
	public function buildAuthenticator()
	{
		$authenticatorClass = $this->configuration->getAuthenticator();
		return new $authenticatorClass($this->configuration, $this);
	}
	
	public function buildEdgeRouter()
	{
		return new Master_EdgeRouter($this->configuration, $this);
	}
	
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
	
	public function buildContainer()
	{
		$db = $this->buildDatabaseConnector();
		return new Container($db);
	}
	
	public function buildFile()
	{
		$db = $this->buildDatabaseConnector();
		$file = new File($db);
		$file->setBaseDir($this->configuration->getDataStore());
		return $file;
	}
}
