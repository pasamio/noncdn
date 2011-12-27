<?php

class NonCDNConfiguration
{
	public $appname = '\NonCDN\Master';
	public $auth_servers = array('http://localhost/~pasamio/usq/noncdn/auth/');
	public $edge_servers = array('http://localhost/~pasamio/usq/noncdn/edge/');
	public $edge_secrets = array('usq');
	public $master_servers = array('http://localhost/~pasamio/usq/master/');
	public $max_token_age = 12; // hours
	public $authenticator = '\NonCDN\Authenticator_Basic';
	
	public $edge_map = array(
			'::1' => array(0),
			'127.0.0.1/8' => array(0)
		);
}

