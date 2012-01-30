<?php
/**
 * @package     NonCDN
 * @subpackage  Transport
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace NonCDN;
defined('NONCDN') or die();

/**
 * An X-SendFile powered transport.
 *
 * @package     NonCDN
 * @subpackage  Transport
 * @since       1.0
 */
class Transport_XSendFile implements Transport
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
	public function deliverFile($path)
	{
		header('Content-type: ' . mime_content_type($path));	
		header('X-Sendfile: '. $path);
	}
}
