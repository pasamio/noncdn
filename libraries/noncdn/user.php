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
class User extends \JDatabaseObject
{
	/** @var    array  A list of tables
	 *  @since  1.0
	 */
	protected $tables = array(
		'primary' => 'users'
		);

	/** @var    array  An array of the keys for the tables.
	 *  @since  1.0
	 */
	protected $keys = array(
		'primary' => array('primary' => 'user_id')
		);

	/**
	 * Load this object by a username
	 *
	 * @param   string  $username  The username of this object.
	 *
	 * @return  User
	 *
	 * @since   1.0
	 */
	public function loadByUsername($username)
	{
		$query = $this->db->getQuery(1);
		$query->select('user_id')->from('users')
			->where('username = ' . $this->db->quote($username));

		$this->db->setQuery($query);

		$userid = $this->db->loadResult();

		if (!$userid)
		{
			throw new \InvalidArgumentException('User not found');
		}
		return $this->load($userid);
	}
}
