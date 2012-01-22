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
	 * Build a container access provider class.
	 *
	 * @return  Container_Access Container access class.
	 *
	 * @since   1.0
	 */
	public function buildContainerAccessProvider()
	{
		$containerAccessProviderClass = $this->configuration->getContainerAccessProvider();
		return new $containerAccessProviderClass();
	}	

	/**
	 * Build an authoriser.
	 *
	 * @return  JAccessAuthoriser  An access validator.
	 *
	 * @since   1.0
	 */
	public function buildAuthoriser()
	{
		return \JAuthorisationFactory::getInstance()->getAuthoriser();
	}

	/**
	 * Build the configured role provider class.
	 *
	 * @param   string  $user  An optional user to do a provider based on user.
	 *
	 * @return  RoleProvider  An instance of a RoleProvider.
	 *
	 * @since   1.0
	 */
	public function buildRoleProvider($user = null)
	{
		$roleProviderClass = $this->configuration->getRoleProvider();
		return new $roleProviderClass($this, $this->configuration);
	}
	
	/**
	 * Build a surrogate JAuthorisationRequestor.
	 *
	 * @param   array  $roles  The roles to use.
	 *
	 * @return  AuthorisationSurrogate  Surrogate with the desired roles
	 *
	 * @since   1.0
	 */
	public function buildAuthorisationSurrogate(array $roles)
	{
		return new AuthorisationSurrogate($roles);	
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

	/**
	 * Build a database connector
	 *
	 * @return  Database  A database connector.
	 *
	 * @since   1.0
	 */
	public function buildDatabaseConnector()
	{
		$db = \JDatabase::getInstance(
			array(
				'driver' => 'pdo',
				'database' => $this->configuration->getAuthDBPath()
			)
		);
		return $db;
	}
}
