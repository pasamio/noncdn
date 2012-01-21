<?php

namespace NonCDN;

class Auth_Configuration extends Configuration
{
	public function getRoleProvider()
	{
		if (isset($this->configuration->roleprovider) &&
			!empty($this->configuration->roleprovider))
		{
			return $this->configuration->roleprovider;
		}
		else
		{
			return 'NonCDN\RoleProvider_Default';			
		}
	}
	
	public function getContainerAccessProvider()
	{
		return 'NonCDN\Container_Access_Default';
	}
	
	public function getCredentialStore()
	{
		return 'NonCDN\CredentialStore_Default';
	}
	
	/**
	 * Get the path to the auth database.
	 *
	 * @return  string  The path to the auth database.
	 *
	 * @since   1.0
	 */ 
	public function getAuthDBPath()
	{
		if (!isset($this->configuration->authdb))
		{
			throw new Exception('Missing auth database in configuration file.');
		}
		return $this->configuration->authdb;
	}
}