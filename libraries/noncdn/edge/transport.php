<?php

namespace NonCDN;

class Edge_Transport
{
	protected $filter = null;
	
	public function __construct()
	{
		$this->filter = new \JFilterInput();	
	}

	public function deliver($container, $path)
	{
		// grab the filename and clean it
		$filename = array_pop($path);
		$filename = $this->cleanPath($filename);
		
		// clean each part of the path removing any '..' that might be there, filter out empty entries
		// and then implode it back into a path; PHP need to work on consistency...
		$path = implode('/', array_filter(array_map(Array($this, 'cleanPath'), $path), 'strlen'));
		$localPath = JPATH_ROOT.'/data/'. $container.'/'.$path.'/'.$filename;
		if(file_exists($localPath))
		{
//			header('Content-Type: application/octet');
//			header('Content-Disposition: attachment; filename="'. basename($path).'"');
			readfile($localPath);
		}
		else
		{
			RequestSupport::terminate(404, 'File Not Found');	
		}
	}
	
	protected function cleanPath($input)
	{
		$count = 0;
		do
		{
			$start = $input;
			$input = str_replace('..', '', $input);
			$count++;
			if($count > 5) { // prevent malicious infinite loop
				RequestSupport::terminate(500, 'Invalid Request');
			}
		} while($start != $input);	
		return $this->filter->clean($input, 'CMD');
	}
	
	
}