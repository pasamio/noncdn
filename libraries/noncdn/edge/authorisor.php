<?php

namespace NonCDN;

class Edge_Authorisor
{
	private $authServers;
	
	public function __construct($authServers)
	{
		$this->authServers = $authServers;	
	}
	
	public function authorise($username, $container)
	{
		return true;
	}	
}