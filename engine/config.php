<?

	/*** DEFAULT SETTINGS ***/
	date_default_timezone_set('America/New_York');

	$_ENV['PRODUCT_NAME']       = 'myproduct';
	$_ENV['HOST_NAME']          = 'localhost';
	$_ENV['INSTALLATION_PATH'] 	= getCwd();
	$_ENV['DEFAULT_APP']        = 'site';
	$_ENV['DEFAULT_TEMPLATE'] 	= 'default';
	$_ENV['DEFAULT_CONTROLLER'] = 'home';
	$_ENV['DEFAULT_ACTION']		= 'index';
	$_ENV['FULL_URL']			= MVC::getFullUrl();
	$_ENV['ENABLE_EMAIL']       = false;

	/*** COOKIES AND AUTHENTICATION SETTINGS ***/
	$_ENV['COOKIE_DOMAIN']      = '';               // Cookie domain
	$_ENV['LOGIN_LIFETIME']     = 1800;             // How long should login last
	$_ENV['LOGIN_SESSION_KEY']  = '___login___';    // $_SESSION[] key for login info

	/*** SCAFFOLD SETTINGS ***/
	$_ENV['CODE_AUTHOR']		= '';
	$_ENV['CODE_AUTHOR_EMAIL']	= '';
	$_ENV['CODE_LICENSE']		= 'http://opensource.org/licenses/gpl-license.php GNU Public License';

	/*** AUTOLOAD ***/
	function __autoload($sClassName) { return MVC::autoload($sClassName); }

	/*** DERIVE APPLICATION NAME ***/
	$_ENV['APP']				= isset($_REQUEST['app']) ? $_REQUEST['app'] : $_ENV['DEFAULT_APP'];
	$_ENV['PATH_TO_APPS'] 		= isset($_ENV['PATH_TO_APPS']) ? $_ENV['PATH_TO_APPS'] : 'apps/';
	$_ENV['PATH_TO_APP'] 		= $_ENV['PATH_TO_APPS'].$_ENV['APP'].'/';
	$_ENV['PATH_TO_COMMON'] 	= $_ENV['PATH_TO_APPS'].'common/';

	/*** DATABASE SETTINGS ***/
	$_ENV['PATH_TO_SCHEMA']     = $_ENV['PATH_TO_COMMON'].'server/schema/';
	$_ENV['DB'] = array(
		'HOST' 		=> 'localhost',
		'NAME' 		=> 'mydatabase',
		'USERNAME' 	=> 'mydatabaseuser',
		'PASSWORD' 	=> 'mypassword'
	);
	$_ENV['PATH_TO_DATA']       = '../data/';                           // Where you want to store your data
	$_ENV['PATH_TO_TEMP']       = $_ENV['PATH_TO_DATA'].'temp/';        // Where uploaded files will temporarily stop by
	$_ENV['PATH_TO_DATABASE']   = $_ENV['PATH_TO_DATA'].'database/';    // Where database stores it's files
	$_ENV['PATH_TO_USERFILES']  = $_ENV['PATH_TO_DATA'].'userfiles/';   // Where uploaded files are stored
	$_ENV['PATH_TO_LOGS']       = $_ENV['PATH_TO_DATA'].'logs/';        // Where logs are stored
	//$_ENV['PATH_TO_LOGS']       = System::getTempDirectory().'/php/'; // TODO

	/*** AUTOLOAD CONFIGURATION ***/
	$_ENV['AUTOLOAD_PATHS'] = array(
		'apps/common/server/classes/',
		'apps/common/server/logic/',
		'apps/common/server/models/',
		$_ENV['PATH_TO_APP'].'server/controllers/',
		$_ENV['PATH_TO_APP'].'server/renderers/',			// Search for app renderers before common ones
		'apps/common/server/renderers/',
	    'apps/common/server/aggregators/',
	    'apps/common/server/interfaces/',
		'apps/common/server/exceptions/'
	);
	$_ENV['AUTOLOAD_PREFIXES'] = array(
		'class.',
		'interface.',
	);
	$_ENV['AUTOLOAD_DEFINITION_PATHS'] = array(
		$_ENV['PATH_TO_APP'].'server/definitions/',
		$_ENV['PATH_TO_COMMON'].'server/definitions/'
	);
	$_ENV['AUTOLOAD_JS_PATHS'] = array(
		$_ENV['PATH_TO_APP'].'client/js/',
		$_ENV['PATH_TO_COMMON'].'client/js/'
	);
	$_ENV['AUTOLOAD_CSS_PATHS'] = array(
		$_ENV['PATH_TO_COMMON'].'client/css/',
		$_ENV['PATH_TO_APP'].'client/css/'
	);
	$_ENV['AUTOLOAD_IMG_PATHS'] = array(
		$_ENV['PATH_TO_APP'].'client/img/',
		$_ENV['PATH_TO_COMMON'].'client/img/',
		$_ENV['PATH_TO_USERFILES']
	);
	$_ENV['INCLUDE_PATHS'] = array(
	    'lib',
	    'lib/oauth'
	);

	$_ENV['PATH_TO_CONTROLLERS'] 	= $_ENV['PATH_TO_APP'].'server/controllers/';
	$_ENV['PATH_TO_VIEWS'] 			= $_ENV['PATH_TO_APP'].'server/views/';
	$_ENV['PATH_TO_TEMPLATES'] 		= $_ENV['PATH_TO_APP'].'server/templates/';
	$_ENV['PATH_TO_XML'] 		    = $_ENV['PATH_TO_APP'].'server/xml/';

	$_ENV['CONTROLLER'] 			= Text::toCamelCase($_REQUEST['page'], false);
	$_ENV['CONTROLLER_CLASS'] 		= Text::toCamelCase($_ENV['CONTROLLER'], true).'Controller';
	$_ENV['ACTION'] 				= Text::toCamelCase($_REQUEST['action'], false);
	$_ENV['VIEW']					= strtolower($_REQUEST['action']);

	$_ENV['PATH_TO_CONTROLLER'] 	= $_ENV['PATH_TO_CONTROLLERS'].'class.'.$_ENV['CONTROLLER_CLASS'].'.php';
	$_ENV['PATH_TO_VIEW'] 			= $_ENV['PATH_TO_VIEWS'].strtolower($_ENV['CONTROLLER']).'/'.$_ENV['VIEW'].'.phtml';

	$_ENV['HTTP_ROOT'] 				= MVC::getHttpRoot();
	$_ENV['MVC_ROOT'] 				= MVC::getMvcRoot();
	$_ENV['APP_ROOT']               = $_ENV['MVC_ROOT'].$_ENV['APP'].'/';
	$_ENV['RESOURCE_ROOT']          = $_ENV['APP_ROOT'].'resource/';

    /*** ERROR LOGGING ***/
	error_reporting(E_ALL ^ E_NOTICE);
	ini_set('error_log', $_ENV['PATH_TO_LOGS'].'errors.log');
	$_ENV['DISPLAY_EXCEPTIONS'] = true;


	MVC::includeApplicationConfiguration();
	MVC::addIncludePaths();


	/***************** RELEASE RELATED *************************/

	$_ENV['USE_COMPILED_CSS']   = false;
	$_ENV['USE_COMPILED_JS']    = false;

	/***********************************************************/
	/**************ENVIRONMENT-SPECIFIC SETTINGS ***************/
	/***********************************************************/
	/*
		Environment example: dev, prod, test, alex, konstantin
		Environment name lives in engine/environment.txt - DO NOT COMMIT THIS FILE!!!!
		Environment-specific configuration is stored in engine/config.[environment name].php
			and used to override/complete settings herein
	*/

	$_ENV['ENVIRONMENT_FILENAME'] = 'engine/environment.txt';       // File holding the name of the environment configuration
	$_ENV['ENVIRONMENT'] = MVC::getEnvironmentName();
	MVC::includeEnvironmentConfiguration();



?>
