<?php

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
