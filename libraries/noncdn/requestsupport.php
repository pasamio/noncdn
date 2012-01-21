<?php

namespace NonCDN;

class RequestSupport
{
	static function terminate($errorcode, $statusline, $message = '', $format = 'html', $errorsubcode = 0)
	{
		// Apache Support
		header('HTTP/1.0 ' . $errorcode . ' ' . $statusline);
		
		// FastCGI Support
		header('Status: ' . $errorcode . ' ' . $statusline);
		
		$response = '';
		
		$error_array = array(
							'error'=>true,
							'errorcode' => $errorcode,
							'errorsubcode' => $errorsubcode,
							'statusline' => $statusline,
							'message' => $message
						);
		
		switch($format)
		{
			case 'json':
				$response = json_encode($error_array);
				break;
			
			case 'html':
			default:
				$response .= '<html><head><title>' . $statusline . '</title></head>';
				$response .= '<body><h1>'. $errorcode . ' ' . $statusline . '</h1>';
				
				if (!empty($message))
				{
					$response .= '<hr /><p>'. $message .'</p>';
				}
				$response .= '</body></html>';
				break;
		}
		die($response);
	}
}
