<?php
/**
 * @package     NonCDN
 * @subpackage  Edge.Controller
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

defined('NONCDN') or die();

/**
 * Edge Test Controller
 *
 * @package     NonCDN
 * @subpackage  Edge.Controller
 * @since       1.0
 */
class Edge_Controller_Test extends BaseController
{
	/**
	 * Execute method
	 *
	 * @param   array  $args  The arguments.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function execute(array $args)
	{
		if (count($args) < 3)
		{
			RequestSupport::terminate(500, 'Invalid arguments');
		}
		$path = $args;
		$user = array_shift($path);
		$container = array_shift($path);

		echo '<p>User "<b>' . $user . '</b>" is authenticating to "<b>' . $container . '</b>"</p>';
		var_dump($this->factory->buildTokenService()->generateToken($user, 0));
	}
}
