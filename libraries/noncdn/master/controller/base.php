<?php
/**
 * @package     NonCDN
 * @subpackage  Master
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

/**
 * Base controller for master node
 *
 * @package     NonCDN
 * @subpackage  Master
 * @since       1.0
 */
class Master_Controller_Base extends BaseController
{
	/**
	 * Deliver a file to the end user authorising a request
	 *
	 * @param   string  $username  Username of the requestor.
	 * @param   array   $args      The arguments for this request.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function deliverFile($username, $args)
	{
		// start to process the args and authorise the user
		$path = $args; // use a copy since we're destructive with it
		$filter = new \JFilterInput;
		$container = $filter->clean(array_shift($path), 'CMD');
		$path = $filter->clean(implode('/', $path), 'PATH');
		$authoriser = $this->factory->buildContainerAuthoriser();
		if (!$authoriser->check_user_access($username, $container))
		{
			RequestSupport::terminate(403, 'Access Denied');
		}

		// At this point we authenticated and authorised the user
		// Now we need to either send them to an edge or deliver directly
		$edgeRouter = $this->factory->buildEdgeRouter();
		$edgeRouter->handleRequest($username, $container, $path);
	}
}
