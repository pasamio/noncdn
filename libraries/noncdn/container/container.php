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
		'primary' => array('primary' => 'container_id')
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
			->where('container_id = ' . (int) $this->container_id)
			->where('path = ' . $this->db->quote(dirname($path)));
		$this->db->setQuery($query);

		$result = true;

		if (!$this->db->loadResult())
		{
			$data = (object) array(
				'file_id' => (int) $file->file_id,
				'container_id' => (int) $this->container_id,
				'path' => dirname($path),
				'filename' => basename($path)
			);

			$result = $this->db->insertObject('container_file', $data);

			$file->use_count++;
			$file->update();
		}
		return $result;
	}

	/**
	 * Add a file from an existing path.
	 *
	 * @param   string  $source       The source file.
	 * @param   string  $destination  The destination file.
	 *
	 * @return  boolean  
	 *
	 * @throws  Exception  If the folder cannot be created.
	 *
	 * @since   1.0
	 */
	public function addFileFromPath($source, $destination)
	{
		$hash = sha1_file($source);

		$file = new \NonCDN\File($this->db);
		$file->loadFileByHash($hash);

		if ($file->file_id)
		{
			// file already in content store
			$this->addFile($file, $destination);
		}
		else
		{
			// make sure we grab some files
			jimport('joomla.filesystem.folder');
			jimport('joomla.filesystem.file');

			// A new file
			// Step 1: Add a new file entry
			$file->create();
			$file->setBaseDir($this->get('data_store'));

			// Step 2: Copy to content store
			$filePath = $file->getFilePath($this->get('data_store'));
			if (!JFolder::create(dirname($filePath)))
			{
				throw new Exception("Failed to create folder: " . dirname($filePath));
			}
			JFile::copy($source, $filePath);

			$file->file_hash = $hash;
			$file->file_size = filesize($filePath);
			$file->use_count = 0;
			$file->update();

			$container->addFile($file, $destination);
		}
		return true;
	}

	/**
	 * Remove a file from this container.
	 *
	 * @param   File    $file  The file object to remove from this container.
	 * @param   string  $path  The destination of the file in the container.
	 *
	 * @return  boolean  Result of removing the file.
	 *
	 * @since   1.0
	 */
	public function removeFile(File $file, $path)
	{
		$query = $this->db->getQuery(1);

		// look for the file first
		$query->delete('container_file')
			->where('file_id = ' . (int) $file->file_id)
			->where('container_id = ' . (int) $this->container_id)
			->where('path = ' . $this->db->quote(dirname($path)))
			->where('filename = ' . $this->db->quote(basename($path)));
		$this->db->setQuery($query);
		$this->db->query();

		$affected = $this->db->getAffectedRows();

		if ($affected)
		{
			$file->use_count -= $affected;
			$file->update();
		}
		return true;
	}

	/**
	 * Get a File object from the container given a particular path.
	 *
	 * @param   string  $path  The destination of the file in the container.
	 *
	 * @return  File  A file contained at this location.
	 *
	 * @throws  Exception  File not found exception.
	 *
	 * @since   1.0
	 */
	public function getFileByPath($path)
	{
		$query = $this->db->getQuery(1);

		// look for the file first
		$query->select('file_id')->from('container_file')
			->where('container_id = ' . (int) $this->container_id)
			->where('path = ' . $this->db->quote(dirname($path)))
			->where('filename = ' . $this->db->quote(basename($path)));
		$this->db->setQuery($query);

		$file_id = $this->db->loadResult();

		if ($file_id)
		{
			$file = new File($this->db);
			$file->load($file_id);
			return $file;
		}
		throw new Exception("File not found");
	}
}
