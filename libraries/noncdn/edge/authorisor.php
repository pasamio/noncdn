<?php
/**
 * @package     NonCDN
 * @subpackage  Edge
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

defined('NONCDN') or die();

/**
 * Edge authorisor
 *
 * @package     NonCDN
 * @subpackage  Edge
 * @since       1.0
 */
class Edge_Authorisor
{
	/** 
	 * @var    $authServers  Authentication servers.
	 * @since  1.0
	 */
	protected $authServers;

	/**
	 * @var    $factory  A factory!
	 * @since  1.0
	 */
	protected $factory;

	/**
	 * Constructor
	 *
	 * @param   Factory  $factory      The factory class.
	 * @param   array    $authServers  Authentication servers.
	 *
	 * @since  1.0
	 */
	public function __construct($factory, $authServers)
	{
		$this->factory = $factory;
		$this->authServers = $authServers;
	}

	/**
	 * Authorise this request
	 *
	 * @param   string  $username   The username of the requestor.
	 * @param   string  $container  The container being accessed.
	 *
	 * @return  boolean  The result of the authorisation.
	 *
	 * @since   1.0
	 */
	public function authorise($username, $container)
	{
		$authoriser = $this->factory->buildContainerAuthoriser();
		return $authoriser->check_user_access($username, $container);
	}
}
