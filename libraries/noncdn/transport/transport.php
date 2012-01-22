<?php
/**
 * @package     NonCDN
 * @subpackage  Transport
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('NONCDN') or die();

/**
 * Transport interface
 *
 * @package     NonCDN
 * @subpackage  Transport
 * @since       1.0
 */
interface Transport
{
	/**
	 * Deliver a file
	 *
	 * @param   string  $path  The path on disk to the file
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function deliverFile($path);
}