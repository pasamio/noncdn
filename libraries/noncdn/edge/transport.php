<?php

namespace NonCDN;

class Edge_Transport
{
	public function deliver($container, $path)
	{
		// grab the filename and clean it
		$filename = array_pop($path);
		$filename = Route::cleanPath($filename);
		
		// clean each part of the path removing any '..' that might be there, filter out empty entries
		// and then implode it back into a path; PHP need to work on consistency...
		$path = implode('/', array_filter(array_map(Array('NonCDN\Route', 'cleanPath'), $path), 'strlen'));
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
}