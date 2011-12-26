<?php

namespace NonCDN;

class Master_Factory extends Factory
{
	public function buildAuthenticator()
	{
		$authenticatorClass = $this->configuration->getAuthenticator();
		return new $authenticatorClass($this->configuration, $this);
	}
}
