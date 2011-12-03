<?php

namespace NonCDN;

class Auth_Configuration extends Configuration
{
	public function getRoleProvider()
	{
		return 'NonCDN\RoleProvider_Default';
	}
	
	public function getContainerAccessProvider()
	{
		return 'NonCDN\Container_Access_Default';
	}
}