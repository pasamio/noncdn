<?php
/**
 * @package     NonCDN
 * @subpackage  File
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

/**
 * File class
 *
 * @package     NonCDN
 * @subpackage  File
 * @since       1.0
 */
class File extends \JDatabaseObject
{
	/** @var    array  A list of tables
	 *  @since  1.0
	 */
	protected $tables = array(
		'primary' => 'files'
		);

	/** @var    array  An array of the keys for the tables.
	 *  @since  1.0
	 */
	protected $keys = array(
		'primary' => array('primary'=>'file_id')
		);

	/** @var    string  Path to the base dir to use to store this file.
	 *  @since  1.0
	 */
	protected $baseDir = null;

	/**
	 * Load a file by a hash
	 *
	 * @param   string  $hash  The hash of the file.
	 *
	 * @return  File  A reference to this file for chaining.
	 *
	 * @since   1.0
	 */
	public function loadFileByHash($hash)
	{
		$query = $this->db->getQuery(1);
		$query->select('*')->from('files')->where('file_hash = ' . $this->db->quote($hash));
		$this->db->setQuery($query);

		$result = $this->db->loadObject();

		if ($result && $result->file_id)
		{
			$this->bind($result);
		}
		return $this;
	}

	/**
	 * Get the path to this file.
	 *
	 * @param   string  $baseDir  The path to the base directory of the file to override.
	 *
	 * @return  string  The completed path to the file
	 *
	 * @since   1.0
	 */
	public function getFilePath($baseDir = null)
	{
		if (is_null($baseDir))
		{
			$baseDir = $this->baseDir;
		}

		$subfolder = (int) ($this->file_id % 1000);
		return $baseDir . $subfolder . '/' . $this->file_id;
	}

	/**
	 * Set the default base directory.
	 *
	 * @param   string  $baseDir  The base directory to set.
	 *
	 * @return  void
	 *
	 * @sicne   1.0
	 */
	public function setBaseDir($baseDir)
	{
		$this->baseDir = $baseDir;
	}
}
