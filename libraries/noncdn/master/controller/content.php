<?php

namespace NonCDN;

class Master_Controller_Content extends BaseController
{
	public function get_content_id($args)
	{
		$filter = new \JFilterInput;
		$container = $filter->clean($_GET['container'], 'CMD');
		$path = $filter->clean($_GET['path'], 'PATH');
		$localPath = JPATH_ROOT.'/data/'. $container.'/'.$path;	
		$this->outputResponse(Array('file_unique_id'=>fileinode($localPath)));
	}
	
	public function get_content($args)
	{
		$filter = new \JFilterInput;
		$contentId = $filter->clean($_GET['file_unique_id'], 'CMD');
		
		
	}
}