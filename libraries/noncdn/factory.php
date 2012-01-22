<?php
/**
 * @package     NonCDN
 * @subpackage  Base
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

/**
 * Abstract Factory
 *
 * @package     NonCDN
 * @subpackage  Base
 * @since       1.0
 */
abstract class Factory
{
	/**
	 * Constructor
	 *
	 * @param   NonCDN_Configuration  $configuration  Base configuration class
	 *
	 * @since  1.0
	 */
	public function __construct($configuration)
	{
		$configurationClass = $configuration->appname . '_Configuration';
		$this->configuration = new $configurationClass($configuration);
	}

	/**
	 * Build a token service
	 *
	 * @param   array  $options  Configuration options for the token service.
	 *
	 * @return  TokenService
	 *
	 * @since   1.0
	 */
	public function buildTokenService($options = Array())
	{
		return new TokenService($this->configuration, $options);
	}

	/**
	 * Build an output handler for a given format.
	 *
	 * @param   string  $format   The format for the handler.
	 * @param   array   $options  Configuration options for the output handler.
	 * 
	 * @return  OutputHandler  The requested output handler.
	 *
	 * @throws  InvalidArgumentException  Invalid Handler specified.
	 *
	 * @since   1.0
	 */
	public function buildOutputHandler($format, $options = Array())
	{
		$handler = 'NonCDN\OutputHandler_' . $format;
		if (class_exists($handler))
		{
			return new $handler($this->configuration, $options);
		}
		else
		{
			throw new \InvalidArgumentException('Invalid Handler');
		}
	}

	/**
	 * Build  a Container.
	 *
	 * @param   array      $options  Options to configure the container.
	 * @param   JDatabase  $db       A database connector to use.
	 *
	 * @return  Container
	 *
	 * @since   1.0
	 */
	public function buildContainer($options = array(), $db = null)
	{
		if (is_null($db))
		{
			$db = $this->buildDatabaseConnector();
		}

		return new Container($db, $options);
	}

	/**
	 * Build a File.
	 *
	 * @param   JDatabase  $db  A database connector to use.
	 *
	 * @return  File  A configured file object.
	 *
	 * @since   1.0
	 */
	public function buildFile($db = null)
	{
		if (is_null($db))
		{
			$db = $this->buildDatabaseConnector();
		}

		$file = new File($db);
		return $file;
	}

	/**
	 * Build a client for an auth node.
	 *
	 * @return  Client_AuthNode
	 *
	 * @since   1.0
	 */
	public function buildAuthNodeClient()
	{
		return new Client_AuthNode($this->configuration);
	}

	/**
	 * Build a container authoriser.
	 *
	 * @return  Container_Authoriser  The container authoriser.
	 *
	 * @since   1.0
	 */
	public function buildAuthoriser()
	{
		return new Container_Authoriser($this->configuration);
	}

	/**
	 * Build a transporter. No relation to the movies.
	 *
	 * @return  Transport
	 *
	 * @since   1.0
	 */
	public function buildTransport()
	{
		$transportClass = $this->configuration->getTransport();
		return new $transportClass($this);
	}

	/**
	 * Build a database connector. Specific to the factory.
	 *
	 * @return  JDatabase  The database connector.
	 *
	 * @since   1.0
	 */
	abstract public function buildDatabaseConnector();
}
