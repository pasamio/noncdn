<?php

namespace NonCDN;

class Master_Configuration extends Configuration
{
	public function getAuthenticator()
	{
		if (isset($this->configuration->authenticator))
		{
			return $this->configuration->authenticator;
		}
		
		// fallback authenticator class
		return '\NonCDN\Authenticator_Basic';
	}
	
	public function getRealm()
	{
		if (isset($this->configuration->auth_realm))
		{
			return $this->configuration->auth_realm;
		}
		
		// fallback realm
		return 'NonCDN Authentication';
	}
	
	public function getEdgeMap()
	{
		if (isset($this->configuration->edge_map))
		{
			return $this->configuration->edge_map;
		}
		return array();
	}
}
