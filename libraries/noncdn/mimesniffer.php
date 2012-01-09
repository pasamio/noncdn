<?php
/**
 * @package     NonCDN
 * @subpackage  MimeSniffer
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

/**
 * MIME Sniffer class
 *
 * @package     NonCDN
 * @subpackage  MimeSniffer
 * @since       1.0
 */
class MimeSniffer
{
	/**
	 * Detect a MIME type from a file.
	 *
	 * @param   string  $filename  The path to a file.
	 *
	 * @return  string  MIME type for this file.
	 *
	 * @throws  Exception  When the MIME detection library isn't available.
	 * @since   1.0
	 */
	public static function detectMimeFromFile($filename)
	{
		$type = '';
		// try to use finfo_open if it is there
		if (function_exists('finfo_open'))
		{
			// We have fileinfo
			$finfo = finfo_open(FILEINFO_MIME);
			$type = finfo_file($finfo, $filename);
			finfo_close($finfo);
		}
		else if(function_exists('mime_content_type'))
		{
			$type = mime_content_type($filename);
		}
		else
		{
			throw new Exception('No MIME detection library available');
		}
		
		return $type;
	}
}
