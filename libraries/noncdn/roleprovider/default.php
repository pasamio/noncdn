<?php
namespace NonCDN;

defined('NONCDN') or die();

class RoleProvider_Default
{
	public function getRoles($username)
	{
		return 'USER';	
	}	
}