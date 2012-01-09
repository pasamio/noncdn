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
				'roles' => 'ARRAY;CMD'
			)
		);

		$this->outputResponse(
			array(
				'error' => false,
				'result' => true,
				'params' => $params
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

		$this->outputResponse(
			array(
				'error' => false,
				'result' => true,
				'params' => $params
			)
		);
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
			RequestSupport::terminate(500, 'Missing container');
		}

		$accessProvider = $this->factory->getContainerAccessProvider($params['container']);
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
