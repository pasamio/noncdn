<?php

namespace NonCDN;

class BaseController
{
	protected $factory = null;

	public function __construct(Factory $factory)
	{
		$this->factory = $factory;
	}
	
	/**
	 * Controller execution
	 */
	public function execute(array $args)
	{	
		$command = array_pop($args);
		
		if(method_exists($this, $command))
		{
			$this->$command($args);
		}
		else
		{
			RequestSupport::terminate(500, 'Invalid command');
		}
	}
	
	protected function outputResponse($data, $format='json')
	{
		$outputHandler = $this->factory->buildOutputHandler($format);
		$outputHandler->output($data);
	}
	
	protected function getParams($params)
	{
		$results = Array();
		$filter = new \JFilterInput();
		foreach($params as $param=>$filterName)
		{
			if(!isset($_REQUEST[$param]))
			{
				$results[$param] = null;
				continue;
			}
			switch($filterName)
			{
			
				default:
					$results[$param] = $filter->clean($_REQUEST[$param], $filterName);
					break;	
			}
		}
		return $results;
	}
}
