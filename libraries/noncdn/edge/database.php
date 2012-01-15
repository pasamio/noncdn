<?php
/**
 * @package     NonCDN
 * @subpackage  Edge
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace NonCDN;

/**
 * Edge Database class
 *
 * @package     NonCDN
 * @subpackage  Edge
 * @since       1.0
 */
class Edge_Database
{
	/** @var    $db  The internal DB connection.
	 *  @since  1.0
	 */
	protected $db;
	
	/**
	 * Constructor
	 *
	 * @param  JDatabase  $db  The database connector to use.
	 *
	 * @since  1.0
	 */
	public function __construct($db)
	{
		$this->db = $db;
	}
	
	/**
	 * Find a cached file content ID for a given container and path
	 *
	 * @param   string  $container  The container to find the file.
	 * @param   string  $path       The path within the container to the file.
	 *
	 * @return  string  The path to the file. If empty then there isn't a local cache.
	 *
	 * @since   1.0
	 */
	public function findFileContentId($container, $path)
	{
		$query = $this->db->getQuery(1);
		$query->select('file_id')->from('container_file')
			->where('container_name = '. $this->db->quote($container))
			->where('fullpath = '. $this->db->quote($path));

		$this->db->setQuery($query);

		return $this->db->loadResult();
	}

	/**
	 * Assign a file content ID to a given container and path.
	 *
	 * @param   integer  $content_id  The content ID of the file.
	 * @param   string   $container   The container to find the file.
	 * @param   string   $path        The path within the container to the file.
	 *
	 * @return  boolean  Result of the operation.
	 *
	 * @since   1.0
	 */
	public function assignFileContentId($content_id, $container, $path)
	{
		/* // TODO: Fix this on sqlite
		$query = $this->db->getQuery(1);
		$query->insert('container_files')
			->set('file_id = ' . (int)$content_id)
			->set('container_name = ' . $this->db->quote($container))
			->set('fullpath = ' . $this->db->quote($path));
			
		$this->db->setQuery($query);

		return $this->db->query();			
		*/
		
		$obj = new \stdClass;
		$obj->file_id = (int)$content_id;
		$obj->container_name = $container;
		$obj->fullpath = $path;

		return $this->db->insertObject('container_file', $obj);
	}
}
