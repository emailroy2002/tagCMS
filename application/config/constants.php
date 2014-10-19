<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|--------------------------------------------------------------------------
| DATE FORMATS
|--------------------------------------------------------------------------
|
| These names are used when working with database table names
|
*/
define('CURRENT_DATE', date('Y-m-d H:i:s', time())); // Standard mysql format for current date


/*
|--------------------------------------------------------------------------
| TABLE NAMES
|--------------------------------------------------------------------------
|
| These names are used when working with database table names
|
*/
define('TABLE_SETTINGS',                        'settings');
define('TABLE_ITEMS',							'items');
define('TABLE_ITEM_DESCRIPTION',				'item_description');
define('TABLE_ITEM_LOCATION',                   'item_location');
define('TABLE_ITEM_BUSINESS_INFO',              'item_business_info');
define('TABLE_PAGES',							'pages');
define('TABLE_CATEGORIES',						'categories');
define('TABLE_USERS',						    'users');
define('TABLE_ROLES',                           'roles');
define('TABLE_COUNTRIES',                       'countries'); 
define('TABLE_REGIONS',                         'regions');
define('TABLE_CITIES',                          'cities');
define('TABLE_LANGUAGES',                       'languages');
define('TABLE_PERMISSIONS',                       'permissions');
define('TABLE_RESOURCES',                       'resources');
/* End of file constants.php */
/* Location: ./application/config/constants.php */