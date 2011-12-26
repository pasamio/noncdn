<?php
namespace NonCDN;

class Factory
{
	public function __construct($configuration)
	{
		$configurationClass = $configuration->appname.'_Configuration';
		$this->configuration = new $configurationClass($configuration);
	}

	public function buildTokenService($options = Array())
	{
		return new TokenService($this->configuration, $options);
	}
	
	public function buildOutputHandler($format, $options = Array())
	{
		$handler = 'NonCDN\OutputHandler_'.$format;
		if(class_exists($handler))
		{
			return new $handler($this->configuration, $options);
		}
		else
		{
			throw new Exception('Invalid Handler');
		}
	}
	
	public function buildAuthNodeClient()
	{
		return new Client_AuthNode($this->configuration);
	}	
	
	public function buildAuthoriser()
	{
		return new Container_Authoriser($this->configuration);
	}
}
