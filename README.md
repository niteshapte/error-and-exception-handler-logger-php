error-and-exception-logger-php
===============================

Hi guys,

I believe that everyone is well aware of errors and exceptions in PHP. And I am sure, everyone must have their own way of handling them.

But there is problem in PHP. PHP can easily trap and log runtime errors but PHP error handlers cannot trap fatal errors. 
And it makes the debugging a troublesome job. I too have faced problems. So, I decided to solve this issue. After going 
through PHP Manual for hours I found a method called register_shutdown_function()


I wrote a class ErrorLogging which catch fatal errors along with notice, warnings and exception. I published it on 
PHPClasses.org. Here is the link – http://www.phpclasses.org/package/6512-PHP-Handle-PHP-fatal-and-non-fatal-execution-errors.html. 
I won first prize for this. :D (Yay!!!). This class will show you the stack-trace of errors/exceptions.

The idea:
Registers an error handler that is capable of a backtrace with the list of functions and arguments used to call the 
code that causes an error, send that information to the current page output or the PHP error log, or send an e-mail 
message to the administrator. The class can also trap fatal errors using a special PHP shutdown callback function.

I know you guys must be wondering what is “web” in parameter. It has been observed that a website is also accessed via mobile. And sometimes web services are created to expose services. So, here “web” means if website is accessed directly then a separate error log file will be created for it, in the location configured in core.php file. So, in order to use this class for logging error when accessed through  mobile or web services or any other thing, make an entry in core.php for it. And in case you are using “any other thing”, you will need to edit the below section in code.

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
