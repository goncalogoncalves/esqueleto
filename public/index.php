<?php

if ($_SERVER['REMOTE_ADDR'] != "127.0.0.1" && $_SERVER['REMOTE_ADDR'] != "000.000.000.000") {
    // we may want to require some file if another ip is accessing this
    //die();
}

// check if session exist
if (!isset($_SESSION)) {
    session_start();
}
ini_set('session.gc_maxlifetime', 86400);

// define a working directory
define('APP_PATH', dirname(__DIR__));
// define app mode
define('APP_MODE', 'development'); // [development,production]

// require autoload
require_once __DIR__.'/../vendor/autoload.php';
echo __DIR__;
// require config
// routes also come from here
require APP_PATH.'/app/config/'.APP_MODE.'.php';

// logger
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Router
$klein = new \Klein\Klein();

// prepare controllers
foreach (array('BaseController','Home', 'Page') as $controller) {
    $controllerPath = APP_PATH."/app/src/esqueleto/controller/$controller.php";
    $klein->with("/$controller", $controllerPath);
}

$klein->respond('*', function ($request, $response, $service) {
    // todo: implement auth if necessary
});

// home route
if (isset($_SESSION['ROUTE_HOME'])) {
    $klein->respond('GET', $_SESSION['ROUTE_HOME'], function ($request, $response) {
        $home = new Devgo\controller\Home();
        $home->indexAction();
    });
}

$klein->respond('GET', '/', function ($request, $response) {
    $home = new Devgo\controller\Home();
    $home->indexAction();
});


// page route
if (isset($_SESSION['ROUTE_PAGE'])) {
    $klein->with($_SESSION['ROUTE_PAGE'], function () use ($klein) {
        $klein->respond('GET', '/[:id]?/[:title]?', function ($request, $response, $service, $app) {
            $pageId = intval($request->param('id'));
            $pageTitle = null !== $request->param('title') ? $request->param('title') : '';
            $page = new Devgo\controller\Page();
            $page->indexAction($pageId, $pageTitle);
        });
    });
}

if (isset($_SESSION['ROUTE_REPORT_ERROR'])) {
    // javascript error report route
    $klein->respond('GET', $_SESSION['ROUTE_REPORT_ERROR'], function ($request, $response) {
        $home = new Devgo\controller\Home();
        $parameters = $_GET;
        $home->javascriptErrorAction($parameters);
    });
}

// router error handler
$klein->onHttpError(function ($code, $router) {
    $errorInfo = '';
    $lastError = error_get_last();
    if (isset($lastError) && $lastError != '') {
        foreach ($lastError as $key => $value) {
            $errorInfo .= ' # '.$key.' - '.$value;
        }
    }
    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $objUtils = new \Devgo\Utils();
    $userIP = $objUtils->getUserIP();
    $forwardedFor = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '';
    $messageLog = 'From '.$userIP.' _ '.$forwardedFor.' // Code : '.$code.' // Url: '.$url.' // Error: '.$errorInfo;

    $log = new Logger('log');
    $log->pushHandler(new StreamHandler(LOG_FILE, Logger::ERROR));
    $log->addError('onHttpError -> '.$messageLog);
    $home = new Devgo\controller\Home();
    $home->errorAction($code);
});

// php error handler
function errorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }
    $errorInfo = '';
    $lastError = error_get_last();
    if (isset($lastError) && $lastError != '') {
        foreach ($lastError as $key => $value) {
            $errorInfo .= ' # '.$key.' - '.$value;
        }
    }

    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $objUtils = new \Devgo\Utils();
    $userIP = $objUtils->getUserIP();
    $forwardedFor = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '';
    $messageLog = 'From '.$userIP.' _ '.$forwardedFor.' // Code: '.$errno.' // Url: '.$url.' // Error: '.$errstr.' - '.$errorInfo.' / '.$errfile.' - '.$errline;

    $log = new Logger('log');
    $log->pushHandler(new StreamHandler(LOG_FILE, Logger::ERROR));
    $log->addError($messageLog);

    /* Don't execute PHP internal error handler */
    return true;
}

$oldErrorHandler = set_error_handler('errorHandler');

$klein->dispatch();
