<?php

namespace NonCDN;

/**
 * Basic Challenge authenticator
 *
 * @package     NonCDN
 * @subpackage  Authenticator
 */
class Authenticator_Basic
{
	/** 
	 * @var    object  Configuration
	 * @since  1.0
	 */
	protected $configuration;
	
	/**
	 * @var    object  Factory
	 * @since  1.0
	 */
	protected $factory;
	
	/**
	 * Constructor
	 *
	 * @param   object  $config   Configuration object
	 * @param   object  $factory  Factory
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function __construct($config, $factory)
	{
		$this->configuration = $config;
		$this->factory = $factory;
	}	
	
	/**
	 * Authenticate a request
	 *
	 * @return  string  username of the requestor.
	 *
	 * @since   1.0
	 */
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
	
	/**
	 * Attempt to authenticate the user
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function requestAuth()
	{
		$realm = $this->configuration->getRealm();		
		header('WWW-Authenticate: Basic realm="'. $realm .'"');
	    header('HTTP/1.0 401 Unauthorized');
	    echo 'Please login to continue.';
	    exit;
	}
}