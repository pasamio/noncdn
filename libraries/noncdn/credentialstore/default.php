<?php

namespace NonCDN;

defined('NONCDN') or die();

class CredentialStore_Default
{
	public function validateCredentials($username, $token)
	{
		// hard coded credentials
		// TODO: flexible credential store
		$details = Array('pasamio'=>'password', 'admin'=>'admin');
		$result = null;
		
		if (isset($details[$username]))
		{
			if ($details[$username] == $token)
			{
				$result = true;
			}
			else
			{
				$result = false;
			}
		}
		return $result;	
	}
}