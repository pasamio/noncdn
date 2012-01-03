<?php

defined('NONCDN') or die();

// Pick up the standard Joomla! Platform (mainline)
require JPATH_PLATFORM.'/import.php';

// Pick up libraries from backports
JLoader::register('JDatabasePDO', __DIR__ . '/joomla-backports/database/database/pdo.php');
JLoader::register('JDatabaseQueryPDO', __DIR__ . '/joomla-backports/database/database/pdoquery.php');

// Modified autoloader for the eBay Content branch (until it gets merged)
class eBayLoader
{
        /**
         * Autoload a Joomla Platform class based on name.
         *
         * @param   string  $class  The class to be loaded.
         *
         * @return  void
         *
         * @since   11.3
         */
        public static function autoload($class)
        {
                // Only attempt autoloading if we are dealing with a Joomla Platform class.
                if ($class[0] == 'J')
                {
                        // Split the class name (without the J) into parts separated by camelCase.
                        $parts = preg_split('/(?<=[a-z])(?=[A-Z])/x', substr($class, 1));

                        // If there is only one part we want to duplicate that part for generating the path.
                        $parts = (count($parts) === 1) ? array($parts[0], $parts[0]) : $parts;

                        // Generate the path based on the class name parts.
                        $path = __DIR__ . '/ebay-content/libraries/joomla/' . implode('/', array_map('strtolower', $parts)) . '.php';

                        // Load the file if it exists.
                        if (file_exists($path))
                        {
                                include $path;
                        }
                }
        }
}

spl_autoload_register(array('eBayLoader', 'autoload'));
