<?php
/**
 * @package     NonCDN
 * @subpackage  Master
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

/**
 * Master Edge Router class
 * This class handles routing a particular request either internally or to an edge.
 *
 * @package     NonCDN
 * @subpackage  Master
 * @since       1.0
 */
class Master_EdgeRouter
{
	/**
	 * @var    Configuration  The configuration for this object.
	 * @since  1.0
	 */
	protected $configuration;

	/**
	 * @var    Factory  The factory for this object.
	 * @since  1.0
	 */
	protected $factory;

	/**
	 * Constructor
	 *
	 * @param   Configuration  $configuration  Configuration object for this instance.
	 * @param   Factory        $factory        Factory object for this instance.
	 *
	 * @since   1.0
	 */
	public function __construct(Configuration $configuration, $factory)
	{
		$this->configuration = $configuration;
		$this->factory = $factory;
	}

	/**
	 * Handle a request either directly or via an edge.
	 *
	 * @param   string  $username   The username of the requestor.
	 * @param   string  $container  The container being requested.
	 * @param   string  $path       The path to the file in the container.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function handleRequest($username, $container, $path)
	{
		$edgeMap = $this->configuration->getEdgeMap();

		$addr = $_SERVER['REMOTE_ADDR'];
		$edges = array();

		// check if we have an exact IP match; great for testing
		if (isset($edgeMap[$addr]))
		{
			$edges = $edgeMap[$addr];
		}
		else
		{
			// look for CIDR formatted blocks
			foreach ($edgeMap as $address => $targetEdges)
			{
				// TODO: match remote_addr using CIDR
				//$edges = $targetEdges;
			}
		}

		// if we're doing a redirect, lets handle that
		if (count($edges))
		{
			$edge = $this->buildRoute($edges, $username, $container, $path);
			header('HTTP/1.1 303 Redirect to edge');
			header('Location: ' . $edge);
			echo 'Redirecting to edge...<a href="' . $edge . '">' . $edge . '</a>';
			exit;
		}


		// so no redirect which means we just deliver locally
		$container = $this->factory->buildContainer()->loadContainerByName($container);
		$file = $container->getFileByPath($path);
		$filePath = $file->getFilePath($this->configuration->getDataStore());
		header('Content-type: ' . mime_content_type($filePath));
		readfile($filePath);
	}

	/**
	 * Build a route for a user to their requested item given a set of edges.
	 *
	 * @param   array   $edges      An array of valid edge nodes for the request.
	 * @param   string  $username   The username of the requestor.
	 * @param   string  $container  The container being requested.
	 * @param   string  $path       The path being requested.
	 *
	 * @return  string  A URI to the target edge including auth token.
	 *
	 * @since   1.0
	 */
	protected function buildRoute($edges, $username, $container, $path)
	{
		$edgeServers = $this->configuration->getEdgeServers();
		$edgeId = $edges[rand(0, count($edges) - 1)];

		$token = $this->factory->buildTokenService()->generateToken($username, $edgeId);

		return $edgeServers[$edgeId] . "auth/$username/$token/$container/$path";
	}
}
