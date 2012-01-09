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
class RoleProvider_Default implements RoleProvider
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
		if (in_array($username, array('pasamio', 'admin')))
		{
			return array('USER');
		}
		return array();
	}
}
