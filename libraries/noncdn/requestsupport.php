<?php

namespace NonCDN;

class RequestSupport
{
	static function terminate($errorcode, $statusline, $message = '')
	{
		if(empty($message))
		{
			$message = $errorcode .' '. $statusline;
		}
		header($errorcode .' '. $statusline);
		die('<html><head><title>'. $message.'</title></head><body><h1>'. $message .'</h1></body></html>');
	}
}
