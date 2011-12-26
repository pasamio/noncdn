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
		return '\NonCDN\Authenticator_Basic';
	}
	
	public function getRealm()
	{
		if (isset($this->configuration->auth_realm))
		{
			return $this->configuration->auth_realm;
		}
		return 'NonCDN Authentication';
	}
}
