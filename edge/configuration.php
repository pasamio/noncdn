<?php

class NonCDNConfiguration
{
	var $appname = '\NonCDN\Edge';
	var $edge_secret = 'usq';
	var $edge_id = 0;
	var $auth_servers = Array('http://localhost/~pasamio/usq/noncdn/auth/');
	var $master_servers = Array('http://localhost/~pasamio/usq/master/');
	var $max_token_age = 12; // hours
}

