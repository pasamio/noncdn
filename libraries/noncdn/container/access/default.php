<?php
namespace NonCDN;

defined('NONCDN') or die();

class Container_Access_Default
{
	public function getRoles($container)
	{
		// permit all users access but deny those with the "ABUSIVE_USER" role
		// e.g. an "abusive user" could be someone who has bulk accessed items
		return Array('permit'=>Array('USER'),'deny'=>Array('ABUSIVE_USER'));	
	}	
}