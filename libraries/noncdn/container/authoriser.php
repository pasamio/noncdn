<?php

namespace NonCDN;

class Container_Authoriser
{
	private $configuration;
	
	public function __construct($configuration)
	{
		$this->configuration = $configuration;
	}	
	
	public function check_user_access($username, $container)
	{
		$server = $this->configuration->getAuthServer();
		
		$data = http_build_query(array('username'=>$username, 'container'=>$container));
		
		$response = file_get_contents($server.'container/check_user_access?'.$data);
		
		$data = json_decode($response);
		
		if (isset($data->error) && $data->error)
		{
			return false;
		}
		
		if (isset($data->result))
		{
			return $data->result;
		}
		
		return false;
	}
}