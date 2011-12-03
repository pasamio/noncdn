<?php

namespace NonCDN;

class Edge_Controller_Test extends BaseController
{
	public function execute(array $args)
	{
		if(count($args) < 3)
		{
			RequestSupport::terminate(500, 'Invalid arguments');
		}
		$path = $args;
		$user = array_shift($path);
		$container = array_shift($path);
		
		echo '<p>User "<b>'.$user.'</b>" is authenticating to "<b>'. $container .'</b>"</p>'; 
		var_dump($this->factory->buildTokenService()->generateToken($user, 0));
	}
}
