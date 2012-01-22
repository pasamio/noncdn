<?php
/**
 * @package     NonCDN
 * @subpackage  RoleProvider
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

defined('NONCDN') or die();

/**
 * Default Role Provider
 *
 * @package     NonCDN
 * @subpackage  RoleProvider
 * @since       1.0
 */
class RoleProvider_Default extends RoleProvider
{
	/**
	 * Get roles from this provider.
	 *
	 * @param   string  $username  The username to retrieve roles from.
	 *
	 * @return  array  The list of roles for this user.
	 *
	 * @since   1.0
	 */
	public function getRoles($username)
	{
		$valid_users = array('pasamio', 'admin', 'demo');
		if (in_array($username, $valid_users))
		{
			return array('USER');
		}

		return array();
	}
}
