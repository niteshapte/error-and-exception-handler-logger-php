<?php
include_once 'core.php';

/**
 * This class handles and logs the error that occurs in the project. Exceptions will also be caught by this class.
 *
 * @package THE movieDB 4.0
 * @author Nitesh Apte
 * @copyright 2014
 * @version 1.0
 * @access private
 */
class ErrorHandler implements Serializable {

	
	/**
	 * Single instance variable for ErrorHandler object
	 * @var $singleInstance
	 * @see getInstance
	 */
	private static $singleInstance;

	/**
	 * @var $MAXLENGTH Maximum length for backtrace message
	 * @see debugBacktrace()
	 */
	private $MAXLENGTH = 64;


	/**
	 * Create the single instance of class
	 * 
	 * @param none
	 * @return Object self::$singleInstance Instance
	 */
	public static function getInstance() {
		
		if(!self::$singleInstance instanceof self) {
			self::$singleInstance = new ErrorHandler();
		}
		return self::$singleInstance;
	}
	
	/**
	 * Private constructor
	 */
	private function __construct() {
		// Nothing to here
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Serializable::serialize()
	 */
	public function serialize() {
		throw new Exception("Serialization is not supported.");
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Serializable::unserialize()
	 */
	public function unserialize($serialized) {
		throw new Exception("Serialization is not supported.");
	}
	
	/**
	 * Override clone method to stop cloning of the object
	 * @throws Exception
	 */
	private function __clone() {
		throw new Exception("Cloning is not supported in singleton class");
	}
	
	/**
	 * Set custom error handler
	 *
	 * @param String $requestFrom
	 * @return none
	 */
	public function enableHandler($requestFrom) {
		
		if($requestFrom == 'web') {
			define('WEB', TRUE);
		}
		if($requestFrom == 'device') {
			define('DEVICE', TRUE);
		}
		if($requestFrom == 'webservice') {
			define('WEBSERVICE', TRUE);
		}
		error_reporting(1);
		set_error_handler(array($this,'customError'), APP_ERROR);
		register_shutdown_function(array($this, 'fatalError'));
	}	

	/**
	 * Custom error logging in custom format
	 *
	 * @param Int $errNo Error number
	 * @param String $errStr Error string
	 * @param String $errFile Error file
	 * @param Int $errLine Error line
	 * @return none
	 */
	public function customError($errNo, $errStr, $errFile, $errLine) {

		if(error_reporting() == 0) {
			return;
		}
		$backTrace = $this->debugBacktrace(2);	

		$errorMessage = "\n<h1>Website Generic Error!</h1>";
		$errorMessage .= "\n<b>ERROR NO : </b><font color='red'>{$errNo}</font>";
		$errorMessage .= "\n<b>TEXT : </b><font color='red'>{$errStr}</font>";
		$errorMessage .= "\n<b>LOCATION : </b><font color='red'>{$errFile}</font>, <b>line</b> {$errLine}, at ".date("F j, Y, g:i a");
		$errorMessage .= "\n<b>Showing Backtrace : </b>\n{$backTrace} \n\n";

		if(SEND_ERROR_MAIL == TRUE) {
			error_log($errorMessage, 1, ADMIN_ERROR_MAIL, "From: ".SEND_ERROR_FROM."\r\nTo: ".ADMIN_ERROR_MAIL);
		}

		if(ERROR_LOGGING==TRUE) {
				
			if(WEB == TRUE) {
				error_log($errorMessage, 3, ERROR_LOGGING_FILE_WEB);
			}
			if(DEVICE == TRUE) {
				error_log($errorMessage, 3, ERROR_LOGGING_FILE_DEVICE);
			}
			if(WEBSERVICE == TRUE) {
				error_log($errorMessage, 3, ERROR_LOGGING_FILE_WEBSERVICE);
			}
		}

		if(DEBUGGING == TRUE) {
			echo "<pre>".$errorMessage."</pre>";
		} else {
			echo SITE_GENERIC_ERROR_MSG;
		}
		exit;
	}

	/**
	 * Build backtrace message
	 *
	 * @param $entriesMade Irrelevant entries in debug_backtrace, first two characters
	 * @return
	 */
	private function debugBacktrace($entriesMade) {
		
		$traceArray = debug_backtrace();
		
		$argsDefine = array();
		
		$traceMessage = '';

		for($i=0;$i<$entriesMade;$i++) {
			array_shift($traceArray);
		}
		
		$defineTabs = sizeof($traceArray) - 1;
		foreach($traceArray as $newArray) {
				
			$defineTabs -= 1;
			if(isset($newArray['class'])) {
				$traceMessage .= $newArray['class'].'.';
			}
			if(!empty($newArray['args'])) {

				foreach($newArray['args'] as $newValue) {
					if(is_null($newValue)) {
						$argsDefine[] = NULL;
					} elseif(is_array($newValue)) {
						$argsDefine[] = 'Array['.sizeof($newValue).']';
					} elseif(is_object($newValue)) {
						$argsDefine[] = 'Object: '.get_class($newValue);
					}
					elseif(is_bool($newValue)) {
						$argsDefine[] = $newValue ? 'TRUE' : 'FALSE';
					} else {
						$newValue = (string)@$newValue;
						$stringValue = htmlspecialchars(substr($newValue, 0, $this->MAXLENGTH));
						if(strlen($newValue)>$this->MAXLENGTH) {
							$stringValue = '...';
						}
						$argsDefine[] = "\"".$stringValue."\"";
					}
				}
			}
			
			$traceMessage .= $newArray['function'].'('.implode(',', $argsDefine).')';
			$lineNumber = (isset($newArray['line']) ? $newArray['line']:"unknown");
			$fileName = (isset($newArray['file']) ? $newArray['file']:"unknown");

			$traceMessage .= sprintf(" # line %4d. file: %s", $lineNumber, $fileName, $fileName);
			$traceMessage .= "\n";
		}
		return $traceMessage;
	}

	/**
	 * Method to catch fatal and parse error
	 *
	 * @param none
	 * @return none
	 */
	public function fatalError() {
		$lastError = error_get_last();
		if($lastError['type'] == 1 || $lastError['type'] == 4 || $lastError['type'] == 16 || $lastError['type'] == 64 || $lastError['type'] == 256 || $lastError['type'] == 4096) {
			$this->customError($lastError['type'], $lastError['message'], $lastError['file'], $lastError['line']);
		}
	}	
}
?>
