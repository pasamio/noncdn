<?php
/**
 * @package     NonCDN
 * @subpackage  Manager
 * @copyright   Copyright (C) 2012 Sam Moffatt  
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
 
defined('NONCDN') or die();

/**
 * Master Factory
 *
 * @package     NonCDN
 * @subpackage  Manager
 * @since       1.0
 */
class MasterFactory
{
	protected $app;
	
	/**
	 *
	 */
	public function __construct(&$app)
	{
		$this->app = $app;
	}
	
	/**
	 *
	 */
	public function buildContainer()
	{
		return new \NonCDN\Container($this->buildDBConnection(), array('dataStore'=>$this->app->get('data_store')));
	}	
	
	/**
	 *
	 */
	public function buildDBConnection()
	{
		$db = JDatabase::getInstance(
			array(
				'driver' => 'pdo',
				'database' => $this->app->get('masterdb')
			)
		);
		return $db;		
	}
}