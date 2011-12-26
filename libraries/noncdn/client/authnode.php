<?php

namespace NonCDN;

class Client_AuthNode
{
	private $configuration;
	public function __construct($configuration)
	{
		$this->configuration = $configuration;
	}	
	
	public function validate_credentials($username, $token)
	{
		$server = $this->configuration->getAuthServer();
		
		$data = http_build_query(array('username'=>$username, 'token'=>$token));
		
		$response = file_get_contents($server.'user/validate_credentials?'.$data);
		
		$result = json_decode($response);
		
		if (isset($result->error) && $result->error)
		{
			return false;
		}
		
		if (isset($result->result))
		{
			return $result->result;
		}
		
		return $result;
	}
}