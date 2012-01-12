<?php
/**
 * @package     NonCDN
 * @subpackage  Master
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

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
}
