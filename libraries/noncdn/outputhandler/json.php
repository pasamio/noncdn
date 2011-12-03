<?php
/**
 * NonCDN JSON Output Handler
 */
namespace NonCDN;

class OutputHandler_JSON
{
	public function output($data)
	{
		echo json_encode($data);
	}	
}