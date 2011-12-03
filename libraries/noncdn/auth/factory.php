<?php

namespace NonCDN;

class Auth_Factory extends Factory
{
	public function getRoleProvider($user = null)
	{
		$roleProviderClass = $this->configuration->getRoleProvider();
		return new $roleProviderClass($this->configuration);	
	}
	
	public function getContainerAccessProvider($container = null)
	{
		$containerProviderClass = $this->configuration->getContainerAccessProvider();
		return new $containerProviderClass($this->configuration);
	}
}
