<?php

namespace NonCDN;

class Edge_Factory extends Factory
{
	public function buildTransport()
	{
		return new Edge_Transport($this->configuration->getMasterServers());	
	}
	
	public function buildAuthorisor()
	{
		$authoriserClass = $this->configuration->getAuthorisorClass();
		return new $authoriserClass($this->configuration->getAuthServers());	
	}
}
