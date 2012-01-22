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
 * Authentication Controller for Containers
 *
 * @package     NonCDN
 * @subpackage  Auth
 * @since       1.0
 */
class Auth_Controller_Container extends BaseController
{
	/**
	 * Check access to a container.
	 *
	 * @param   array  $args  Arguments for this request.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function check_access($args)
	{
		$params = $this->getParams(
			Array(
				'container' => 'CMD',
				'action' => 'CMD',
				'roles' => 'ARRAY;CMD'
			)
		);

		// validate the container and roles
		if (empty($params['container']) || empty($params['roles']))
		{
			RequestSupport::terminate(500, 'Missing details', 'No container or roles specified.', 'json');
		}
		
		// if the action param isn't set, default to read
		$action = 'read';
		if (!empty($params['action']))
		{
			$action = $params['action'];
		}		
		
		$surrogate = $this->factory->buildAuthorisationSurrogate($params['roles']);

		// get the relevant access provider
		$containerAccessProvider  = $this->factory->buildContainerAccessProvider();

		// build ourselves an authoriser
		$authoriser = $this->factory->buildAuthoriser($containerAccessProvider);
		$authoriser->setRules($containerAccessProvider->getRoles($params['container']));

		// and check our access
		$access = $authoriser->isAllowed($action, $surrogate);
		if (is_null($access))
		{
			$access = false;
		}

		$this->outputResponse(
			array(
				'error' => false,
				'result' => $access,
			)
		);
	}

	/**
	 * Check user access.
	 *
	 * @param   array  $args  Arguments for this request.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function check_user_access($args)
	{
		$params = $this->getParams(
			array(
				'container' => 'CMD',
				'username' => 'CMD'
			)
		);
		
		if (empty($params['container']) && empty($params['username']))
		{
			RequestSupport::terminate(500, 'Missing details', 'No container or username specified.', 'json');	
		}

		// if the action param isn't set, default to read
		$action = 'read';
		if (!empty($params['action']))
		{
			$action = $params['action'];
		}

		try 
		{
			$roleProvider = $this->factory->buildRoleProvider();
			$roles = $roleProvider->getRoles($params['username']);
		} 
		catch(\Exception $e)
		{
			RequestSupport::terminate(500, 'Invalid User', 'The user specified is not valid.', 'json');	
		}
		
		try
		{
			$surrogate = $this->factory->buildAuthorisationSurrogate($roles);
	
			// get the relevant access provider
			$containerAccessProvider  = $this->factory->buildContainerAccessProvider();
	
			// build ourselves an authoriser
			$authoriser = $this->factory->buildAuthoriser($containerAccessProvider);
			$authoriser->setRules($containerAccessProvider->getRoles($params['container']));
	
			// and check our access
			$access = $authoriser->isAllowed($action, $surrogate);
			if (is_null($access))
			{
				$access = false;
			}
	
			$this->outputResponse(
				array(
					'error' => false,
					'result' => $access,
				)
			);
		} 
		catch(\Exception $e)
		{
			RequestSupport::terminate(500, 'Unable to validate user', 'The system could not validate the user had access.', 'json');	
		}
	}

	/**
	 * Get a list of roles for this container.
	 *
	 * @param   array  $args  Arguments for this request.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function get_roles($args)
	{
		$params = $this->getParams(
			array(
				'container' => 'CMD'
			)
		);

		if (!strlen($params['container']))
		{
			RequestSupport::terminate(500, 'Missing container', 'This request should include a container', 'json');
		}

		$accessProvider = $this->factory->buildContainerAccessProvider($params['container']);
		$roles = $accessProvider->getRoles($params['container']);

		$this->outputResponse(
			array(
				'error' => false,
				'container' => $params['container'],
				'roles' => $roles
			)
		);
	}
}
