<?php

namespace NonCDN;

class Configuration
{
	protected $configuration = null;

	public function __construct(\NoNCDNConfiguration $configuration)
	{
		$this->configuration = $configuration;
	}

	public function getEdgeSecret($edge)
	{
		if(isset($this->configuration->edge_secrets[$edge]))
		{
			return $this->configuration->edge_secrets[$edge];
		}
		else
		{
			throw new InvalidConfiguration('Missing Edge Secret');
		}
	}

	public function getMaxTokenAge()
	{
		return $this->configuration->max_token_age;
	}
	
	/**
	 * Return a list of available auth servers.
	 *
	 * @return  array  All available auth servers
	 *
	 * @since   1.0
	 */
	public function getAuthServers()
	{
		return $this->configuration->auth_servers;	
	}
	
	/**
	 * Return a random auth server.
	 *
	 * @return  string  The URI to an auth server.
	 * 
	 * @since   1.0
	 */
	public function getAuthServer()
	{
		$authServers = $this->getAuthServers();	
		$serverId = rand(0, count($authServers) - 1); // rand is inclusive, isn't it nice?
		return $authServers[$serverId];
	}
	
	/**
	 * Get a list of edge servers.
	 *
	 * @return  array  List of edge servers.
	 *
	 * @since   1.0
	 */
	public function getEdgeServers()
	{
		return $this->configuration->edge_servers;
	}
	
	/**
	 * Get the configured transport class.
	 *
	 * @return  string  The transport class to use.
	 *
	 * @since   1.0
	 */
	public function getTransport()
	{
		return $this->configuration->transport;
	}
}
