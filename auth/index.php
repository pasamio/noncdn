<?php
// Include Guard
define('NONCDN', 1);

// error reporting while developing
error_reporting(-1);
ini_set('display_errors', 1);

// our configuration!
require 'configuration.php';

// off we go!
require dirname(dirname(__FILE__)).'/app.php';
