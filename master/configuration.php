<?php

class NonCDNConfiguration
{
	var $appname = '\NonCDN\Master';
	var $auth_servers = Array('http://localhost/~pasamio/usq/noncdn/auth/');
	var $edge_servers = Array('http://localhost/~pasamio/usq/noncdn/edge/');
	var $edge_secrets = Array('usq');
	var $master_servers = Array('http://localhost/~pasamio/usq/master/');
	var $max_token_age = 12; // hours
	var $authenticator = '\NonCDN\Authenticator_Basic';
}

