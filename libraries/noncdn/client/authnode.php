<?php

namespace NonCDN;

/**
 * Client for the auth node
 *
 * @package     NonCDN
 * @subpackage  Client
 */
class Client_AuthNode
{
	/**
	 * @var    object  configuration class
	 * @since  1.0
	 */
	private $configuration;
	
	/**
	 * Constructor
	 *
	 * @param   object  $configuration  Configuration object.
	 *
	 * @return  void
	 */
	public function __construct($configuration)
	{
		$this->configuration = $configuration;
	}	
	
	/**
	 * Validate credentials for a username and token.
	 *
	 * @param   string  $username  Username of the requestor.
	 * @param   string  $token     An authentication token for the request.
	 *
	 * @return  boolean  If the credentials are valid or not.
	 *
	 * @since   1.0
	 */	 
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