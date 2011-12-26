<?php

namespace NonCDN;

class Authenticator_Basic
{
	protected $configuration;
	protected $factory;
	
	public function __construct($config, $factory)
	{
		$this->configuration = $config;
		$this->factory = $factory;
	}	
	
	public function authenticate()
	{
		if (!isset($_SERVER['PHP_AUTH_USER'])) {
		    $this->requestAuth();
		}
	    
	    $authServerClient = $this->factory->buildAuthNodeClient();
		$result = $authServerClient->validate_credentials($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
		
		if(!$result)
		{
			$this->requestAuth();
		}
		return $_SERVER['PHP_AUTH_USER'];
	}
	
	public function requestAuth()
	{
		$realm = $this->configuration->getRealm();		
		header('WWW-Authenticate: Basic realm="'. $realm .'"');
	    header('HTTP/1.0 401 Unauthorized');
	    echo 'Please login to continue.';
	    exit;
	}
}