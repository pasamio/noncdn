<?php
/**
 * @package     NonCDN
 * @subpackage  Stream
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace NonCDN;

/**
 * Simple stream filter to duplicate a stream to disk.
 *
 * @package     NonCDN
 * @subpackage  Stream
 * @since       1.0
 */
class Stream_Filter_Duplicate extends \PHP_User_Filter
{
	/**
	 *
	 */
	public function filter($in, $out, &$consumed, $closing) {

        while ($bucket = stream_bucket_make_writeable($in)) {
            $result = fwrite($this->fh, $bucket->data);
            if($result != strlen($bucket->data))
            {
	            throw new Exception('stream duplication error');
            }
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }
        return PSFS_PASS_ON;
    }
    
    /**
     * 
     */
    public function onCreate()
    {
    	if (isset($this->params['outFile']))
    	{
	    	$this->fh = fopen($this->params['outFile'], 'w');
	    	return (bool) $this->fh;
    	}
    	else
    	{
	    	return false;
    	}
    }
    
    /**
     * 
     */
    public function onClose()
   	{
	   	fclose($this->fh);
   	}
}
