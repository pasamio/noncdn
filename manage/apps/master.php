<?php
/**
 * @package     NonCDN
 * @subpackage  Manager
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('NONCDN') or die();


require __DIR__ . '/controller.php';

/**
 * Manager Master Controller.
 * Used to manipulate functions of the master node.
 *
 * @package     NonCDN
 * @subpackage  Manager.Master
 *
 * @since       1.0
 */
class MasterController extends Controller
{
	/**
	 * Execute this CLI instance
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function execute()
	{
		if (count($this->input->args) < 2)
		{
			$this->out("Usage: {$this->executable} master <command> [options]");
			exit(1);
		}

		$command = $this->input->args[1];

		// ignore this otherwise we'll infinite loop
		if (in_array($command, array('execute', '__construct')))
		{
			$this->out('Invalid command specified');
			exit(1);
		}

		// check we support the command
		if (method_exists($this, $command))
		{
			// check it's public
			$method = new ReflectionMethod($this, $command);
			if ($method->isPublic())
			{
				$this->$command();
			}
			else
			{
				$this->out('Invalid command specified');
				exit(1);
			}
		}
		else
		{
			$this->out('Invalid command specified');
			exit(1);
		}
	}

	/**
	 * Get a connection to the database.
	 *
	 * @return  JDatabase
	 *
	 * @since   1.0
	 */
	protected function getDBConnection()
	{
		return $this->factory->buildDBConnection();
	}

	// ---------------------------------------------------------------------------------------------
	// CONTAINER MANAGEMENT CODE
	// ---------------------------------------------------------------------------------------------

	/**
	 * Create a new container in the system.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function create_container()
	{
		if (count($this->input->args) < 3)
		{
			$this->out("Usage: {$this->executable} master create_container <container name> [--description=description]");
			exit(1);
		}

		$container = strtolower($this->input->args[2]);
		$this->out("Creating a new container '$container'...");
		$db = $this->getDBConnection();
		$containerObj = new stdClass;
		$containerObj->container_name = $container;
		$containerObj->description = $this->input->get('description');
		$containerObj->expiry = $this->input->get('expiry');

		try
		{
			$db->insertObject('containers', $containerObj);
			$this->out("Done.");
		}
		catch (JDatabaseException $e)
		{
			$this->out('Failed to create container: ' . $e->getMessage());
		}
	}

	/**
	 * List containers available in the system
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function list_containers()
	{
		$db = $this->getDBConnection();
		$query = $db->getQuery(1);
		$query->select('*')->from('containers');
		$db->setQuery($query);

		$containers = $db->loadObjectList();

		foreach ($containers as $container)
		{
			$this->out("{$container->container_id}\t{$container->container_name}\t\t{$container->description}");
		}
	}

	/**
	 * Alter a container in the system.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function alter_container()
	{
		if (count($this->input->args) < 3)
		{
			$this->out("Usage: {$this->executable} master alter_container <container name> [--description=description]");
			exit(1);
		}

		$container = strtolower($this->input->args[2]);
		$db = $this->getDBConnection();
		$query = $db->getQuery(true);

		$query->select('container_id')->from('containers')->where('container_name = ' . $db->quote($container));
		$db->setQuery($query);
		try
		{
			$container_id = $db->loadResult();

			if (empty($container_id))
			{
				throw new Exception("Container $container doesn't exist.");
			}
		}
		catch (Exception $e)
		{
			$this->out('Failed to alter container: ' . $e->getMessage());
		}

		$containerObj = new stdClass;
		$containerObj->container_id = $container_id;
		$containerObj->container_name = strtolower($this->input->get('container_name', $container));
		$containerObj->description = $this->input->get('description', null, 'raw');
		$containerObj->expiry = $this->input->get('expiry', null);

		try
		{
			$db->updateObject('containers', $containerObj, 'container_id');
		}
		catch (JDatabaseException $e)
		{
			$this->out('Failed to alter container: ' . $e->getMessage());
		}
	}

	/**
	 * Delete a container from the system.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function delete_container()
	{
		if (count($this->input->args) < 3)
		{
			$this->out("Usage: {$this->executable} master delete_container <container name>");
			exit(1);
		}

		$container = strtolower($this->input->args[2]);
		$this->out("Deleting container '$container'...");
		$db = $this->getDBConnection();
		$db->setQuery('DELETE FROM containers WHERE container_name = "' . $container . '"');

		try
		{
			$db->query();
			$this->out("Done.");
		}
		catch (JDatabaseException $e)
		{
			$this->out('Failed to remove container: ' . $e->getMessage());
		}
	}

	// ---------------------------------------------------------------------------------------------
	// CONTAINER CONTENT MANAGEMENT CODE
	// ---------------------------------------------------------------------------------------------

	/**
	 * Add file to container
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function add_file()
	{
		// validate the arg count
		if (count($this->input->args) < 5)
		{
			$this->out("Usage: {$this->executable} master add_file <container name> [source] [destination]");
			exit(1);
		}

		// grab some values
		$container_name = strtolower($this->input->args[2]);
		$source = $this->input->args[3];
		$destination = $this->input->args[4];

		// validate our input file exists
		if (!file_exists($source))
		{
			$this->out("Source file missing!");
			exit(1);
		}

		$container = $this->factory->buildContainer();
		$container->loadContainerByName($container_name);
		// validate our container exists
		if (!$container->container_id)
		{
			$this->out("Invalid container specified.");
			exit(1);
		}
		$this->out('Adding ' . basename($source) . ' to ' . dirname($destination) . ' in ' . $container);

		$container->addFileFromPath($source, $destination);
	}

	/**
	 * Remove file from container
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function remove_file()
	{
		// validate the arg count
		if (count($this->input->args) < 4)
		{
			$this->out("Usage: {$this->executable} master remove_file <container name> <path>");
			exit(1);
		}

		// grab some values
		$container_name = strtolower($this->input->args[2]);
		$path = $this->input->args[3];

		$container = $this->factory->buildContainer();
		$container->loadContainerByName($container_name);
		// validate our container exists
		if (!$container->container_id)
		{
			$this->out("Invalid container specified.");
			exit(1);
		}

		$file = $container->getFileByPath($path);
		$container->removeFile($file, $path);
	}

	/**
	 * List files in a container
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function list_files()
	{
		// validate the arg count
		if (count($this->input->args) < 3)
		{
			$this->out("Usage: {$this->executable} master list_files <container name> [base path]");
			exit(1);
		}

		$containerName = $this->input->args[2];

		$basePath = '';
		if (isset($this->input->args[3]) && strlen($this->input->args[3]))
		{
			$basePath = '/' . trim($this->input->args[3], '/') . '/';
			$basePath = str_replace('//', '/', $basePath);
		}

		$container = $this->factory->buildContainer();
		$container->loadContainerByName($containerName);

		$files = $container->getFiles($basePath);

		foreach ($files as $file)
		{
			$this->out($file->path . $file->filename);
		}
	}
}
