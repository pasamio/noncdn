<?php
/**
 * @package     NonCDN
 * @subpackage  Manager
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

/**
 * Container class
 *
 * @package     NonCDN
 * @subpackage  Container
 * @since       1.0
 */
class Container extends \JDatabaseObject
{
	/** @var    array  A list of tables
	 *  @since  1.0
	 */
	protected $tables = array(
		'primary' => 'containers'
		);

	/** @var    array  An array of the keys for the tables.
	 *  @since  1.0
	 */
	protected $keys = array(
		'primary' => array('primary'=>'container_id')
		);

	/**
	 * Load this container by name.
	 *
	 * @param   string  $container_name  The name of the container to load.
	 *
	 * @return  Container  A reference to this object for chaining.
	 *
	 * @since   1.0
	 */
	public function loadContainerByName($container_name)
	{
		$query = $this->db->getQuery(true);
		$query->select('*')->from('containers')->where('container_name = ' . $this->db->quote($container_name));
		$this->db->setQuery($query);
		$result = $this->db->loadObject();

		if (is_object($result))
		{
			$this->bind($result);
		}
		return $this;
	}

	/**
	 * Add a file to this container
	 *
	 * @param   File    $file  The file object to add to this container.
	 * @param   string  $path  The destination of the file in the container.
	 *
	 * @return  boolean  Result of adding the file.
	 *
	 * @since   1.0
	 */
	public function addFile(File $file, $path)
	{
		$query = $this->db->getQuery(1);

		// look for the file first
		$query->select('count(*)')->from('container_file')
			->where('file_id = ' . (int) $file->file_id)
			->where('container_id = ' . (int) $this->container_id);
		$this->db->setQuery($query);

		if (!$this->db->loadResult())
		{
			$data = (object) array(
				'file_id' => (int) $file->file_id,
				'container_id' => (int) $this->container_id,
				'path' => dirname($path),
				'filename' => basename($path)
			);

			return $this->db->insertObject('container_file', $data);
		}
		return true;
	}
}
