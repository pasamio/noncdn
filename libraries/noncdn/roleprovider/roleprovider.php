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
abstract class RoleProvider
{
	/**
	 * @var    $factory  A factory object for this class.
	 * @since  1.0
	 */
	protected $factory;

	/**
	 * @var    $configuration  A configuration object for this class.
	 * @since  1.0
	 */
	protected $configuration;

	/**
	 * Constructor
	 *
	 * @param   Factory        $factory        A factory to set for this class.
	 * @param   Configuration  $configuration  A configuration to set for this class.
	 *
	 * @since  1.0
	 */
	public function __construct($factory, $configuration)
	{
		$this->factory = $factory;
		$this->configuration = $configuration;
	}

	/**
	 * Get roles from this provider.
	 *
	 * @param   string  $username  The username to retrieve roles from.
	 *
	 * @return  array  The list of roles for this user.
	 *
	 * @since   1.0
	 */
	abstract public function getRoles($username);
}
