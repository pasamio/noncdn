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
 * Authentication user controller
 *
 * @package     NonCDN
 * @subpackage  Auth
 * @since       1.0
 */
class Auth_Controller_User extends BaseController
{
	/**
	 * Validate a given set of credentials.
	 *
	 * @param   array  $args  Arguments for this request.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function validate_credentials($args)
	{
		$params = $this->getParams(
			array(
				'username' => 'CMD',
				'token' => 'CMD',
				'auth_method' => 'CMD'
			)
		);

		$username = $params['username'];
		$token = $params['token'];

		if (!strlen($username))
		{
			RequestSupport::terminate(500, 'Missing username');
		}

		$error = false;
		$credentialStore = $this->factory->getCredentialStore();
		$result = $credentialStore->validateCredentials($username, $token);

		if (is_null($result))
		{
			$result = false;
			$error = true;
		}

		$this->outputResponse(array('error' => $error, 'result' => $result));
	}

	/**
	 * Get roles for this container.
	 *
	 * @param   array  $args  Arguments for this request.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function get_roles($args)
	{
		$params = $this->getParams(array('username' => 'CMD'));
		$username = $params['username'];

		if (!strlen($username))
		{
			RequestSupport::terminate(500, 'Missing username');
		}

		try
		{
			$roleProvider = $this->factory->getRoleProvider();

			$roles = $roleProvider->getRoles($username);
			
			$this->outputResponse(
				array(
					'error' => false,
					'username' => $username,
					'roles' => $roles
				)
			);
		}
		catch (\Exception $e)
		{
			$this->outputResponse(
				array(
					'error' => true,
					'username' => $username,
					'msg' => $e->getMessage()
				)
			);
		}
	}
}
