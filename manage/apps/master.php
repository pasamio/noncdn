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
			$this->out("Usage: {$this->executable} master [command] [options]");
			exit(1);
		}

		$command = $this->input->args[1];

		// ignore this otherwise we'll infinite loop
		if (in_array($command, array('execute', '__construct')))
		{
			$this->out('Invalid command specified');
			exit(1);
		}

		// :D
		if (method_exists($this, $command))
		{
			$this->$command();
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
	public function getConnection()
	{
		$db = JDatabase::getInstance(array('driver'=>'pdo', 'database'=>'sqlite:/Users/pasamio/Sites/usq/noncdn/db/master.db'));
		return $db;
	}

	/**
	 * Add a container to the system.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function add_container()
	{
		if (count($this->input->args) < 3)
		{
			$this->out("Usage: {$this->executable} master add_container [container name] [--description=description]");
			exit(1);
		}

		$container = $this->input->args[2];
		$this->out("Adding a new container '$container'...");
		$db = $this->getConnection();
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
			$this->out('Failed to add container: ' . $e->getMessage());
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
			$this->out("Usage: {$this->executable} master delete_container [container name]");
			exit(1);
		}

		$container = $this->input->args[2];
		$this->out("Deleting container '$container'...");
		$db = $this->getConnection();
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
}
