<?php

namespace NonCDN;


class Edge_Controller_Server extends BaseController
{	
	public function invalidate_content()
	{
		$params = $this->getParams(Array('container'=>'CMD', 'file_path'=>'VAR'));
		echo '<p>invalidate content!</p>';
	}
	
	public function invalidate_user()
	{
		$params = $this->getParams(Array('username'=>'CMD'));		
		echo '<p>invalidate user</p>';
	}
	
	public function invalidate_authorisations()
	{
		$params = $this->getParams(Array('container'=>'CMD'));
		echo '<p>invalidate authorisations</p>';
	}
}
