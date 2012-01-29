# Installation

## Introduction
NonCDN is designed to be deployed with three major nodes:

- master nodes
- auth nodes
- edge nodes

Master nodes serve as the main repository for content and it's mapping. Master nodes provide routing to edge nodes as well.

Auth nodes provide authentication and authorisation services. Auth nodes are designed to be integrated into authentication systems.

Edge nodes are technically optional and provide the ability to deliver content to users at an edge closest to them as determined by the master node.


## Deployment

Each node should be deployed by exposing via a web server each of the respective folders found in the root directory of the repository, e.g. master, auth and edge.

Each node should be configured by the configuration file found in the directory.

The first step is to check out the Git repository:

	git clone git://github.com/pasamio/noncdn.git /var/lib/noncdn
	git submodule update --init

NonCDN uses submodules from the Joomla Project and the eBay Software Foundation.


If the noncdn repository is checked out to /var/lib/noncdn, then the following Apache configuration would work within a VirtualHost directive:


	ServerName master.noncdn.org
	ServerAdmin example@noncdn.org
	DocumentRoot "/var/lib/noncdn/master"
	DirectoryIndex index.php
	CustomLog "/var/lib/noncdn/logs/master_access_log" combined
	ErrorLog "/var/lib/noncdn/logs/master_error_log"

This sets up the document root in the given sub-directory matching the server name. In this situation it is the master node however you could swap in auth and edge respectively.

For the document root, you will need a directory directive like this:

	Options -Indexes
	AllowOverride All

This will disable indexes and add the ability for the .htaccess file to operate properly.


From here you need to copy the htaccess.txt file to .htaccess and configure accordingly. You can also migrate these settings into your main Apache configuration file as well.

You also need to update the configuration.php file to feature the correct settings for your environment.

This is required for at least master and auth servers. While it is suggested that each application lives within it's own server for reliability purposes obviously they can all be run on the same server.