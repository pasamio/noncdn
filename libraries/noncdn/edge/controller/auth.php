<?php

namespace NonCDN;

/**
 * authentication controller
 */
class Edge_Controller_Auth extends BaseController
{
	/**
	 * Controller execution
	 */
	public function execute(array $args)
	{
		if(count($args) < 4)
		{
			RequestSupport::terminate(500, 'Invalid data');
		}
		$path = $args; // create a copy of the args just in case
		$username = array_shift($path); // pull out the username
		$token = array_shift($path); // pull out the token
		$container = array_shift($path); // pull out the container
		// and now path will be the path to the target file

		$filter = new \JFilterInput();
		$username = $filter->clean($username, 'CMD');
		$token = $filter->clean($token, 'VAR');
		$container = $filter->clean($container, 'CMD');

		try {	
			$tokenservice = $this->factory->buildTokenService(Array('check_token_age'=>false));
			$validToken = $tokenservice->validateToken($username, $token);
		} catch(Exception $e)
		{
			RequestSupport::terminate(500, 'Token Error');
		}
		if(!$validToken)
		{
			RequestSupport::terminate(403, 'Invalid Token');
		}
		
		$authorisor = $this->factory->buildAuthorisor();
		if(!$authorisor->authorise($username, $container))
		{
			RequestSupport::terminate(403, 'No access');
		}
		
		// we have a valid token, now we need to deliver some content
		$edgeTransport = $this->factory->buildEdgeTransport();
		$edgeTransport->deliver($container, $path);
	}
}
