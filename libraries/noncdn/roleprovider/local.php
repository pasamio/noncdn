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
 * Local Role Provider
 *
 * @package     NonCDN
 * @subpackage  RoleProvider
 * @since       1.0
 */
class RoleProvider_Local extends RoleProvider
{
	/**
	 * Get roles from the local database.
	 *
	 * @param   string  $username  The username to retrieve roles from.
	 *
	 * @return  array  The list of roles for this user.
	 *
	 * @since   1.0
	 */
	public function getRoles($username)
	{
		$db = $this->factory->buildDatabaseConnector();
		
		$user = new User($db);
		$user->loadByUsername($username);

		$query = $db->getQuery(1);
		$query->select('role_name')->from('roles r')
			->innerJoin('user_role ur ON r.role_id = ur.role_id')
			->where('ur.user_id = ' . $user->user_id);

		$db->setQuery($query);

		$results = $db->loadResultArray();

		if (!is_array($results))
		{
			$results = array();
		}

		return $results;
	}
}
