<?php
namespace NonCDN;

defined('NONCDN') or die();

class Auth_Controller_User extends BaseController
{
	public function validate_credentials($args)
	{
		$params = $this->getParams(Array('username'=>'CMD','token'=>'CMD','auth_method'=>'CMD'));
		
		$username = $params['username'];
		$token = $params['token'];

		if(!strlen($username))
		{
			RequestSupport::terminate(500, 'Missing username');
		}		
		
		$result = false;
		switch($username)
		{
			case 'pasamio':
				if($token == 'password')
				{
					$result = true;	
				}
				break;
			case 'admin':
				if($token == 'admin')
				{
					$result = true;	
				}
				break;
		}

		$this->outputResponse(array('error'=>false, 'result'=>$result));
	}
	
	public function get_roles($args)
	{
		$params = $this->getParams(Array('username'=>'CMD'));
		$username = $params['username'];
		
		if(!strlen($username))
		{
			RequestSupport::terminate(500, 'Missing username');
		}
		$roleProvider = $this->factory->getRoleProvider();
	
		$roles = $roleProvider->getRoles($username);
		
		$this->outputResponse(array('error'=>false, 'username'=>$username, 'roles'=>$roles));
	}
}