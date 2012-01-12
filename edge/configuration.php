<?php

class NonCDNConfiguration
{
	public $appname = '\NonCDN\Edge';
	public $edge_secret = 'usq';
	public $edge_id = 0;
	public $auth_servers = Array('http://localhost/~pasamio/usq/noncdn/auth/');
	public $master_servers = Array('http://localhost/~pasamio/usq/master/');
	public $max_token_age = 12; // hours
	public $edgedb = 'sqlite:/Users/pasamio/Sites/usq/noncdn/db/edge.db';
	public $cachedir = '/Users/pasamio/Sites/usq/noncdn/edgecache';
}

