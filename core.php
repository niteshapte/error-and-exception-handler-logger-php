
/**
* Web root configuration
*/
define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT']);


/**
* Administrator account details
*/
define('ADMIN_NAME', 'Administrator');
define('ADMIN_EMAIL', 'administrator@email.com');


/**
 * Error Handling Values
 */
define('APP_ERROR', E_ALL ^ E_NOTICE);
define('DEBUGGING', TRUE);
define('ADMIN_ERROR_MAIL', 'administrator@email.com');
define('SEND_ERROR_MAIL', FALSE);
define('SEND_ERROR_FROM', 'errors@email.com');
define('IS_WARNING_FATAL', TRUE);
define('ERROR_LOGGING', TRUE);
define('WEB', TRUE);
define('DEVICE', FALSE);
define('WEBSERVICE', FALSE);
define('ERROR_LOGGING_FILE_WEB', SITE_ROOT.'/logs/web/logs.ErrorWeb.log');
define('ERROR_LOGGING_FILE_WEBSERVICE', SITE_ROOT.'/logs/webservice/logs.ErrorWebService.log');
define('ERROR_LOGGING_FILE_DEVICE', SITE_ROOT.'/logs/mobile/logs.ErrorDevice.log');
define('SITE_GENERIC_ERROR_MSG', '<h1>Portal Error!</h1>');
