<?php
/**
 * @package     NonCDN
 * @subpackage  Auth
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

defined('NONCDN') or die();

/**
 * Auth configuration class
 *
 * @package     NonCDN
 * @subpackage  Auth
 * @since       1.0
 */
class Auth_Configuration extends Configuration
{
	/**
	 * Get a role provider
	 *
	 * @return  string  The class name for a role provider
	 *
	 * @since   1.0
	 */
	public function getRoleProvider()
	{
		if (isset($this->configuration->roleprovider)
			&& !empty($this->configuration->roleprovider))
		{
			return $this->configuration->roleprovider;
		}
		return '\NonCDN\RoleProvider_Default';
	}

	/**
	 * Get access provider
	 *
	 * @return  string  The class name for an access provider.
	 *
	 * @since   1.0
	 */
	public function getContainerAccessProvider()
	{
		if (isset($this->configuration->accessprovider)
			&& !empty($this->configuration->accessprovider))
		{
			return $this->configuration->accessprovider;
		}
		return '\NonCDN\Container_Access_Default';
	}

	/**
	 * Get a credential store.
	 *
	 * @return  string  The class name for a credential store.
	 *
	 * @since   1.0
	 */
	public function getCredentialStore()
	{
		if (isset($this->configuration->credentialstore)
			&& !empty($this->configuration->credentialstore))
		{
			return $this->configuration->credentialstore;
		}
		return '\NonCDN\CredentialStore_Default';
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
