<?php
namespace NonCDN;

defined('NONCDN') or die();

class Auth_Controller_Container extends BaseController
{
	public function check_access($args)
	{
		$params = $this->getParams(Array('container'=>'CMD', 'roles'=>'ARRAY;CMD'));
		$this->outputResponse(Array(
			'error'=>false,
			'result'=>false,
			'params'=>$params
		));
	}
	
	public function check_user_access($args)
	{
		$params = $this->getParams(array('container'=>'CMD', 'username'=>'CMD'));
		$this->outputResponse(Array(
			'error'=>false,
			'result'=>false,
			'params'=>$params
		));
	}
	
	public function get_roles($args)
	{
		$params = $this->getParams(Array('container'=>'CMD'));
		
		if(!strlen($params['container']))
		{
			RequestSupport::terminate(500, 'Missing container');
		}
		
		$accessProvider = $this->factory->getContainerAccessProvider($params['container']);
		$roles = $accessProvider->getRoles($params['container']);
		
		$this->outputResponse(Array(
			'error'=>false, 
			'container'=>$params['container'], 
			'roles'=>$roles
		));
	}
}