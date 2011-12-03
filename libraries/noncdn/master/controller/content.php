<?php

namespace NonCDN;

class Master_Controller_Content extends BaseController
{
	public function get_content_id($args)
	{
		$container = $_GET['container'];
		$path = $_GET['path'];
		$localPath = JPATH_ROOT.'/data/'. $container.'/'.$path;	
		$this->outputResponse(Array('file_unique_id'=>fileinode($localPath)));
	}
}