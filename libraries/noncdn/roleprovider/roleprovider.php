<?php
/**
 * @package     NonCDN
 * @subpackage  RoleProvider
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
 
namespace NonCDN;
 
 /**
 * Role Provider interface.
 *
 * @package     NonCDN
 * @subpackage  RoleProvider
 * @since       1.0
 */
interface RoleProvider
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
	public function getRoles($username);
}