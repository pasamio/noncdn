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
 * Container authoriser class.
 *
 * @package     NonCDN
 * @subpackage  Container
 * @since       1.0
 */
class Container_Authoriser
{
	/**
	 * @var    $configuration  Internal configuration object.
	 * @since  1.0
	 */
	protected $configuration;

	/**
	 * Construct
	 *
	 * @param   Configuration  $configuration  A configuration object.
	 *
	 * @since  1.0
	 */
	public function __construct($configuration)
	{
		$this->configuration = $configuration;
	}

	/**
	 * Check user access
	 *
	 * @param   string  $username   Username to check.
	 * @param   string  $container  Container to check.
	 *
	 * @return  boolean  The result.
	 *
	 * @since   1.0
	 */
	public function check_user_access($username, $container)
	{
		$server = $this->configuration->getAuthServer();

		$data = http_build_query(
			array(
				'username' => $username,
				'container' => $container
				)
			);

		$response = @file_get_contents($server . '/container/check_user_access?' . $data);

		// the request failed
		if (empty($response))
		{
			return false;
		}

		$data = json_decode($response);

		// the request resulted in an error on the other side
		if (isset($data->error) && $data->error)
		{
			return false;
		}

		// the request had a result, so return this
		if (isset($data->result))
		{
			return $data->result;
		}

		// otherwise successful except no result so fail
		return false;
	}
}
