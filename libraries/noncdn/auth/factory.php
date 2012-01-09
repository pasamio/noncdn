<?php
/**
 * @package     NonCDN
 * @subpackage  Auth
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

/**
 * Auth Factory
 *
 * @package     NonCDN
 * @subpackage  Auth
 * @since       1.0
 */
class Auth_Factory extends Factory
{
	/**
	 * Get the configured role provider class.
	 *
	 * @param   string  $user  An optional user to do a provider based on user.
	 *
	 * @return  RoleProvider  An instance of a RoleProvider.
	 *
	 * @since   1.0
	 */
	public function getRoleProvider($user = null)
	{
		$roleProviderClass = $this->configuration->getRoleProvider();
		return new $roleProviderClass($this->configuration);
	}

	/**
	 * Get a container access provider class.
	 *
	 * @param   string  $container  An optional container to do a provider based on container.
	 *
	 * @return  RoleProvider  An instance of a RoleProvider.
	 *
	 * @since   1.0
	 */
	public function getContainerAccessProvider($container = null)
	{
		$containerProviderClass = $this->configuration->getContainerAccessProvider();
		return new $containerProviderClass($this->configuration);
	}

	/**
	 * Get a credential store.
	 *
	 * @return  CredentialStore  The configured credential store.
	 *
	 * @since   1.0
	 */
	public function getCredentialStore()
	{
		$credentialStoreClass = $this->configuration->getCredentialStore();
		return new $credentialStoreClass($this->configuration);
	}
}
