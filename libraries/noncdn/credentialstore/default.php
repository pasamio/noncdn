<?php
/**
 * @package     NonCDN
 * @subpackage  CredentialStore
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

defined('NONCDN') or die();

/**
 * Default credential store
 *
 * @package     NonCDN
 * @subpackage  CredentialStore
 * @since       1.0
 */
class CredentialStore_Default
{
	/**
	 * Validate a given set of credentials.
	 *
	 * @param   string  $username  The username to validate.
	 * @param   string  $token     A supplied token to validate.
	 *
	 * @return  boolean  The validated result.
	 *
	 * @since   1.0
	 */
	public function validateCredentials($username, $token)
	{
		// hard coded credentials for demo purposes
		$details = array(
				'pasamio' => 'password',
				'admin' => 'admin',
				'demo' => 'demo'
			);

		$result = null;

		// check if the user name is there
		if (isset($details[$username]))
		{
			// validate the token matches
			if ($details[$username] == $token)
			{
				$result = true;
			}
			else
			{
				$result = false;
			}
		}
		return $result;
	}
}
