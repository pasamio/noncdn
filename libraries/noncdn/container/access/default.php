<?php
/**
 * @package     NonCDN
 * @subpackage  Container
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

defined('NONCDN') or die();

/**
 * Container default access controller.
 *
 * @package     NonCDN
 * @subpackage  Container
 * @since       1.0
 */
class Container_Access_Default
{
	/**
	 * Get roles for a given container.
	 *
	 * @param   Container  $container  The container to retrieve the roles.
	 *
	 * @return  array  An array of roles permitted and denied access.
	 *
	 * @since   1.0
	 */
	public function getRoles($container)
	{
		// permit write to admins
		// permit read all users access 
		// deny those with the "ABUSIVE_USER" role
		// e.g. an "abusive user" could be someone who has bulk accessed items
		return array(
				'write'=> array('ADMIN' => true),
				'read' => array(
					'USER' => true,
					'ABUSIVE_USER' => false
				)
			);
	}
}
