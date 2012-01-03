<?php
/**
* @version		$Id: PDO.php 11316 2008-11-27 03:11:24Z pasamio $
* @package		Joomla.Framework
* @subpackage	Database
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is within the rest of the framework
defined('JPATH_ROOT') or die();

/**
 * PDO database driver
 *
 * @package		Joomla.Framework
 * @subpackage	Database
 * @since		1.7
 */
class JDatabasePDO extends JDatabase
{
	/**
	 * The database driver name
	 *
	 * @var string
	 */
	var $name			= 'PDO';

	/**
	 *  The null/zero date string
	 *
	 * @var string
	 */
	var $nullDate		= '0000-00-00 00:00:00';

	/**
	 * Quote for named objects
	 *
	 * @var string
	 */
	var $nameQuote		= "'";

	/**
	* Database object constructor
	*
	* @access	public
	* @param	array	List of options used to configure the connection
	* @since	1.5
	* @see		JDatabase
	*/
	function __construct( $options )
	{
		$database		= array_key_exists('database',$options)			? $options['database']			: '';
		$prefix			= array_key_exists('prefix', $options)			? $options['prefix']			: 'jos_';
		$user			= array_key_exists('user', $options)			? $options['user']				: '';
		$password		= array_key_exists('password',$options)			? $options['password']			: '';
		$driver_options	= array_key_exists('driver_options', $options) 	? $options['driver_options'] 	: Array();

		// perform a number of fatality checks, then return gracefully
		if (!class_exists( 'PDO' )) {
			$this->_errorNum = 1;
			$this->_errorMsg = 'The PDO adapter "PDO" is not available.';
			return;
		}

		// connect to the server
		if (!($this->_resource = new PDO( $database, $user, $password, $options ))) {
			$this->_errorNum = 2;
			$this->_errorMsg = 'Could not connect to PDO';
			return;
		}
		
		// finalize initialization
		parent::__construct($options);
	}

	/**
	 * Database object destructor
	 *
	 * @return boolean
	 * @since 1.5
	 */
	function __destruct()
	{
		$return = false;
		if (!is_null($this->_resource)) {
			unset($this->_resource);
		}
		return $return;
	}

	/**
	 * Test to see if the PDO class is available
	 *
	 * @static
	 * @access public
	 * @return boolean  True on success, false otherwise.
	 */
	static function test()
	{
		return (class_exists( 'PDO' ));
	}

	/**
	 * Determines if the connection to the server is active.
	 *
	 * @access	public
	 * @return	boolean
	 * @since	1.5
	 */
	function connected()
	{
		if(!is_null($this->_resource)) {
			return true;
		}
		return false;
	}

	/**
	 * Select a database for use
	 * For PDO this is a nop
	 * 
	 * @access	public
	 * @param	string $database
	 * @return	boolean True if the database has been successfully selected
	 * @since	1.5
	 */
	function select($database)
	{

		return true;
	}

	/**
	 * Determines UTF support
	 *
	 * @access	public
	 * @return boolean True - UTF is supported
	 */
	function hasUTF()
	{
		return true;
	}

	/**
	 * Custom settings for UTF support
	 *
	 * @access	public
	 */
	function setUTF()
	{
		// nop!
	}

	/**
	 * Get a database escaped string
	 *
	 * @param	string	The string to be escaped
	 * @param	boolean	Optional parameter to provide extra escaping
	 * @return	string
	 * @access	public
	 * @abstract
	 */
	function getEscaped( $text, $extra = false )
	{
		/*$result = $this->_resource->quote( $text );
		if ($extra) {
			$result = addcslashes( $result, '%_' );
		}*/
		return $result;
	}
	
	function quote( $text ) {
		return $this->_resource->quote( $text );
	}

	/**
	 * Execute the query
	 *
	 * @access	public
	 * @return mixed A database resource if successful, FALSE if not.
	 */
	function query()
	{
		if (is_null($this->_resource)) {
			return false;
		}

		// Take a local copy so that we don't modify the original query and cause issues later
		$sql = $this->sql;
		$sql = str_replace("'0'", 'NULL', $sql); // dodgy!
		$sql = str_replace('(0', '(NULL', $sql); // still dodgy!
		if ($this->limit > 0 || $this->offset > 0) {
			$sql .= ' LIMIT '.$this->offset.', '.$this->limit;
		}
		if ($this->debug) {
			$this->_ticker++;
			$this->_log[] = $sql;
		}
		$this->_errorNum = 0;
		$this->_errorMsg = '';
		$this->_lastsql = $sql;
		
		$this->_cursor = $this->_resource->query( $sql );

		if (!$this->_cursor)
		{
			$this->_errorNum = $this->_resource->errorCode();
			$errorinfo = $this->_resource->errorInfo();
			$this->_errorMsg = $errorinfo[2] ." SQL=$sql";

			if (JError::$legacy && $this->debug) {
				JError::raiseError(500, 'JDatabasePDO::query: '.$this->_errorNum.' - '.$this->_errorMsg );
			}
			else
			{
				throw new JDatabaseException('JDatabasePDO::query: '.$this->_errorNum.' - '.$this->_errorMsg );
			}
			return false;
		}
		return $this->_cursor;
	}

	/**
	 * Description
	 *
	 * @access	public
	 * @return int The number of affected rows in the previous operation
	 * @since 1.0.5
	 */
	function getAffectedRows()
	{
		return $this->_cursor->rowCount();
	}

	/**
	 * Execute a batch query
	 *
	 * @access	public
	 * @return mixed A database resource if successful, FALSE if not.
	 */
	function queryBatch( $abort_on_error=true, $p_transaction_safe = false)
	{
		$this->_errorNum = 0;
		$this->_errorMsg = '';
		if ($p_transaction_safe) {
			$this->sql = rtrim($this->sql, "; \t\r\n\0");
			$this->sql = 'BEGIN;' . $this->sql . '; COMMIT;';
		}
		$query_split = $this->splitSql($this->sql);
		$error = 0;
		foreach ($query_split as $command_line) {
			$command_line = trim( $command_line );
			if ($command_line != '') {
				$this->_cursor = $this->_resource->query( $command_line );
				if ($this->debug) {
					$this->_ticker++;
					$this->_log[] = $command_line;
				}
				if (!$this->_cursor) {
					$error = 1;
					$this->_errorNum .= $this->_resource->errorCode() . ' ';
					$this->_errorMsg .= $this->_resource->errorInfo() ." SQL=$command_line <br />";
					if ($abort_on_error) {
						return $this->_cursor;
					}
				}
			}
		}
		return $error ? false : true;
	}

	/**
	 * Diagnostic function
	 *
	 * @access	public
	 * @return	string
	 */
	function explain()
	{
		$temp = $this->sql;
		$this->sql = "EXPLAIN $this->sql";

		if (!($cur = $this->query())) {
			return null;
		}
		$first = true;

		$buffer = '<table id="explain-sql">';
		$buffer .= '<thead><tr><td colspan="99">'.$this->getQuery().'</td></tr>';
		while ($row = $cur->fetch(PDO::FETCH_ASSOC)) {
			if ($first) {
				$buffer .= '<tr>';
				foreach ($row as $k=>$v) {
					$buffer .= '<th>'.$k.'</th>';
				}
				$buffer .= '</tr>';
				$first = false;
			}
			$buffer .= '</thead><tbody><tr>';
			foreach ($row as $k=>$v) {
				$buffer .= '<td>'.$v.'</td>';
			}
			$buffer .= '</tr>';
		}
		$buffer .= '</tbody></table>';
		unset( $cur );

		$this->sql = $temp;

		return $buffer;
	}

	/**
	 * Description
	 *
	 * @access	public
	 * @return int The number of rows returned from the most recent query.
	 */
	function getNumRows( $cur=null )
	{
		if (!is_null($cur))
		{
			return $cur->rowCount();
		}
		else
		{
			return $this->_cursor->rowCount();
		}
	}

	/**
	 * This method loads the first field of the first row returned by the query.
	 *
	 * @access	public
	 * @return The value returned in the query or null if the query failed.
	 */
	function loadResult()
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($row = $cur->fetch(PDO::FETCH_NUM)) {
			$ret = $row[0];
		}
		unset( $cur );
		return $ret;
	}

	/**
	 * Load an array of single field results into an array
	 *
	 * @access	public
	 */
	function loadResultArray($numinarray = 0)
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = $cur->fetch(PDO::FETCH_NUM)) {
			$array[] = $row[$numinarray];
		}
		unset( $cur );
		return $array;
	}

	/**
	* Fetch a result row as an associative array
	*
	* @access	public
	* @return array
	*/
	function loadAssoc()
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($array = $cur->fetch(PDO::FETCH_ASSOC)) {
			$ret = $array;
		}
		unset( $cur );
		return $ret;
	}

	/**
	* Load a assoc list of database rows
	*
	* @access	public
	* @param string The field name of a primary key
	* @return array If <var>key</var> is empty as sequential list of returned records.
	*/
	function loadAssocList( $key='' )
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = $cur->fetch(PDO::FETCH_ASSOC)) {
			if ($key) {
				$array[$row[$key]] = $row;
			} else {
				$array[] = $row;
			}
		}
		unset( $cur );
		return $array;
	}

	/**
	* This global function loads the first row of a query into an object
	*
	* @access	public
	* @return 	object
	*/
	function loadObject( )
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($object = $cur->fetch(PDO::FETCH_OBJ)) {
			$ret = $object; // array to object!
		}
		unset( $cur );
		return $ret;
	}

	/**
	* Load a list of database objects
	*
	* If <var>key</var> is not empty then the returned array is indexed by the value
	* the database key.  Returns <var>null</var> if the query fails.
	*
	* @access	public
	* @param string The field name of a primary key
	* @return array If <var>key</var> is empty as sequential list of returned records.
	*/
	function loadObjectList( $key='' )
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = $cur->fetch(PDO::FETCH_OBJ)) {
			if ($key) {
				$array[$row->$key] = $row;
			} else {
				$array[] = $row;
			}
		}
		unset( $cur );
		return $array;
	}

	/**
	 * Description
	 *
	 * @access	public
	 * @return The first row of the query.
	 */
	function loadRow()
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($row = $cur->fetch(PDO::FETCH_NUM)) {
			$ret = $row;
		}
		unset( $cur );
		return $ret;
	}

	/**
	* Load a list of database rows (numeric column indexing)
	*
	* @access public
	* @param string The field name of a primary key
	* @return array If <var>key</var> is empty as sequential list of returned records.
	* If <var>key</var> is not empty then the returned array is indexed by the value
	* the database key.  Returns <var>null</var> if the query fails.
	*/
	function loadRowList( $key=null )
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = $cur->fetch(PDO::FETCH_NUM)) {
			if ($key !== null) {
				$array[$row[$key]] = $row;
			} else {
				$array[] = $row;
			}
		}
		unset( $cur );
		return $array;
	}

	/**
	 * Inserts a row into a table based on an objects properties
	 *
	 * @access	public
	 * @param	string	The name of the table
	 * @param	object	An object whose properties match table fields
	 * @param	string	The name of the primary key. If provided the object property is updated.
	 */
	function insertObject( $table, &$object, $keyName = NULL )
	{
		$fmtsql = 'INSERT INTO '.$this->nameQuote($table).' ( %s ) VALUES ( %s ) ';
		$fields = array();
		foreach (get_object_vars( $object ) as $k => $v) {
			if (is_array($v) or is_object($v) or $v === NULL) {
				continue;
			}
			if ($k[0] == '_') { // internal field
				continue;
			}
			$fields[] = $this->nameQuote( $k );
			$values[] = $this->isQuoted( $k ) ? $this->Quote( $v ) : (int) $v;
		}

		if (count($fields))
		{
			$this->setQuery( sprintf( $fmtsql, implode( ",", $fields ) ,  implode( ",", $values ) ) );
		}
		else
		{
			// we can't insert an empty value using a query that'd work with mysql
			// but we can do this and it will work
			$this->setQuery('INSERT INTO ' . $this->nameQuote($table) . ' DEFAULT VALUES');
		}

		if (!$this->query()) {
			return false;
		}
		$id = $this->insertid();
		
		if ($keyName && $id) {
			$object->$keyName = $id;
		}
		return true;
	}

	/**
	 * Description
	 *
	 * @access public
	 * @param [type] $updateNulls
	 */
	function updateObject( $table, &$object, $keyName, $updateNulls=true )
	{
		$fmtsql = 'UPDATE '.$this->nameQuote($table).' SET %s WHERE %s';
		$tmp = array();
		foreach (get_object_vars( $object ) as $k => $v)
		{
			if( is_array($v) or is_object($v) or $k[0] == '_' ) { // internal or NA field
				continue;
			}
			if( $k == $keyName ) { // PK not to be updated
				$where = $keyName . '=' . $this->Quote( $v );
				continue;
			}
			if ($v === null)
			{
				if ($updateNulls) {
					$val = 'NULL';
				} else {
					continue;
				}
			} else {
				$val = $this->isQuoted( $k ) ? $this->Quote( $v ) : (int) $v;
			}
			$tmp[] = $this->nameQuote( $k ) . '=' . $val;
		}
		$this->setQuery( sprintf( $fmtsql, implode( ",", $tmp ) , $where ) );
		return $this->query();
	}

	/**
	 * Description
	 *
	 * @access public
	 */
	function insertid()
	{
		return $this->_resource->lastInsertId();
	}

	/**
	 * Description
	 *
	 * @access public
	 */
	function getVersion()
	{
		return 'N/A (PDO)';
	}

	/**
	 * Assumes database collation in use by sampling one text field in one table
	 *
	 * @access	public
	 * @return string Collation in use
	 */
	function getCollation ()
	{
		return 'N/A (PDO)';
	}

	/**
	 * Description
	 *
	 * @access	public
	 * @return array A list of all the tables in the database
	 */
	function getTableList()
	{
		$this->setQuery("SELECT name FROM sqlite_master WHERE type = 'table' ORDER BY 'name'");
		return $this->loadResultArray();
	}

	/**
	 * Shows the CREATE TABLE statement that creates the given tables
	 *
	 * @access	public
	 * @param 	array|string 	A table name or a list of table names
	 * @return 	array A list the create SQL for the tables
	 */
	function getTableCreate( $tables )
	{
		settype($tables, 'array'); //force to array
		$result = array();

		foreach ($tables as $tblval) {
			$this->setQuery( 'SELECT sql FROM sqlite_master WHERE tbl_name = ' . $this->quote( $tblval ) );
			$row = $this->loadResult();
			$result[$tblval] = $row;
		}

		return $result;
	}

	/**
	 * Retrieves information about the given tables
	 *
	 * @access	public
	 * @param 	array|string 	A table name or a list of table names
	 * @param	boolean			Only return field types, default true
	 * @return	array An array of fields by table
	 */
	function getTableFields( $tables, $typeonly = true )
	{
		return array();
	}


	public function dropTable( $tableName, $ifExists = false )
	{
		return false;
	}

	public function escape( $string, $extra = false )
	{
		return addslashes($string);
	}

	public function fetchArray( $cursor = null )
	{
		return array();
	}

	public function fetchAssoc( $cursor = null )
	{
		return array();
	}

	public function fetchObject( $cursor = null, $class = 'stdClass' )
	{
		return new $class;
	}

	public function freeResult( $cursor = null )
	{
		// ?
	}

	public function getQuery( $new = false )
	{
		if($new)
		{
			return new JDatabaseQueryPDO();
		}
		return $this->sql;
	}

	public function getTableColumns( $table, $typeOnly = true )
	{
		$this->setQuery('PRAGMA table_info ('. $table.')');
		$columns = $this->loadObjectList();

		$results = array();
		foreach($columns as $column)
		{
			if($typeOnly)
			{
				$results[$column->name] = $column->type;
			}
			else
			{
				$results[$column->name] = $column;
			}
		}
		return $results;
	}


	public function getTableKeys( $tables )
	{
die('get table keys');
		foreach($tables as $table)
		{
			
		}
	}

	public function renameTable( $oldTable, $newTable, $backup = null, $prefix = null)
	{
		$this->setQuery('ALTER TABLE ' . $oldTable . ' RENAME TO ' . $newTable);
		return $this->query(); 
	}

	public function lockTable( $tableName )
	{
	}

	public function transactionCommit()
	{
		$this->setQuery('COMMIT TRANSACTION');
		$this->query();
	}

	public function transactionRollback()
	{
		$this->setQuery('ROLLBACK TRANSACTION');
		$this->query();
	}

	public function transactionStart()
	{
		$this->setQuery('BEGIN TRANSACTION');
		$this->query();
	}

	public function unlockTables()
	{
	}
}
