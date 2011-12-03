<?php

namespace NonCDN;

class Configuration
{
	protected $configuration = null;

	public function __construct(\NoNCDNConfiguration $configuration)
	{
		$this->configuration = $configuration;
	}

	public function getEdgeSecret($edge)
	{
		if(isset($this->configuration->edge_secrets[$edge]))
		{
			return $this->configuration->edge_secrets[$edge];
		}
		else
		{
			throw new InvalidConfiguration('Missing Edge Secret');
		}
	}

	public function getMaxTokenAge()
	{
		return $this->configuration->max_token_age;
	}
}
