<?php
namespace NonCDN;

defined('NONCDN') or die();

class RoleProvider_Default
{
	public function getRoles($username)
	{
		if (in_array($username, Array('pasamio', 'admin')))
		{
			return Array('USER');
		}
		return Array();
	}
}