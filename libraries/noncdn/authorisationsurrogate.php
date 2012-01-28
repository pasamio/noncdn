<?php
/**
 * @package     NonCDN
 * @subpackage  Base
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

defined('NONCDN') or die();

/**
 * Surrogate authorisation requestor
 *
 * @package     NonCDN
 * @subpackage  Base
 * @since       1.0
 */
class AuthorisationSurrogate implements \JAuthorisationRequestor
{
	/**
	 * @var    $identities  An array of identities.
	 * @since  1.0
	 */
	protected $identities;

	/**
	 * Constructor
	 *
	 * @param   array  $identities  Identities to provide.
	 *
	 * @since  1.0
	 */
	public function __construct($identities)
	{
		$this->identities = $identities;
	}

	/**
	 * Get the identities for this requestor.
	 *
	 * @return  array  The identities in this surrogate.
	 *
	 * @since   1.0
	 */
	public function getIdentities()
	{
		return $this->identities;
	}
}
