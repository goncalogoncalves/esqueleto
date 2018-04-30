<?php

$YOUR_IP = '000.000.000.000';
$arrayAllowedIPs = array('127.0.0.1', $YOUR_IP);

if (!in_array($_SERVER['REMOTE_ADDR'], $arrayAllowedIPs)) {
    echo "WITHOUT PERMISSIONS";
    die;
}

date_default_timezone_set("Europe/Lisbon");

// check if session exist
if (!isset($_SESSION)) {
    session_start();
}
ini_set('session.gc_maxlifetime', 86400);

// define a working directory and app mode
define('APP_PATH', dirname(__DIR__));
define('APP_MODE', 'development'); // [development,production]

// require autoload and configs
require_once __DIR__ . '/../vendor/autoload.php';
require_once APP_PATH . '/app/config/app.php';

if (APP_MODE == "development") {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

// logger
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

// Router
$klein = new \Klein\Klein();

// prepare controllers
foreach (array('BaseController', 'HomeController', 'PageController', 'DevController') as $controller) {
    $controllerPath = APP_PATH . "/app/src/controller/$controller.php";
    $klein->with("/$controller", $controllerPath);
}

$klein->respond('GET', '/', function ($request, $response) {
    $home = new Esqueleto\controller\HomeController();
    $home->indexAction();
});

// home route
if (isset($_SESSION['ROUTE_HOME'])) {
    $klein->respond('GET', $_SESSION['ROUTE_HOME'], function ($request, $response) {
        $home = new Esqueleto\controller\HomeController();
        $home->indexAction();
    });
}

// get page route
$klein->with("/page", function () use ($klein) {
    $klein->respond('GET', '/[:id]?/[:title]?', function ($request, $response, $service, $app) {
        $pageId = intval($request->param('id'));
        $pageTitle = null !== $request->param('title') ? $request->param('title') : '';
        $page = new Esqueleto\Controller\PageController();
        $page->viewAction($pageId, $pageTitle);
    });
});
$klein->respond('GET', "/get-page", function ($request, $response) {
    $page = new Esqueleto\Controller\PageController();
    $parameters = $_GET;
    $page->getPageAction($parameters);
});

if (isset($_SESSION['ROUTE_REPORT_ERROR'])) {
    // javascript error report route
    $klein->respond('GET', $_SESSION['ROUTE_REPORT_ERROR'], function ($request, $response) {
        $home = new Esqueleto\controller\HomeController();
        $parameters = $_GET;
        $home->javascriptErrorAction($parameters);
    });
}

// generic route
if (isset($_SESSION['ROUTE_GENERIC'])) {
    $routes = json_decode($_SESSION["routes"]);
    for ($i = 0; $i < sizeof($routes); $i++) {
        $tempName = $routes[$i]->name;
        $tempValue = $routes[$i]->value;
        if (substr($tempName, 0, 13) == 'ROUTE_GENERIC') {
            $klein->with($tempValue, function () use ($klein) {
                $klein->respond('GET', '/[:name]?', function ($request, $response, $service, $app) {
                    $home = new Esqueleto\Controller\HomeController();
                    $home->genericRouteAction();
                });
            });
        }
    }
}

// DEV
$klein->with('/dev', function () use ($klein) {
    $klein->respond('GET', '/[:function]?', function ($request, $response, $service, $app) {
        $function = null !== $request->param('function') ? $request->param('function') : '';
        $dev = new Esqueleto\Controller\DevController();
        if ($function != null) {
            call_user_func(array($dev, $function . 'Action'));
        } else {
            $dev->indexAction();
        }
    });
});

// router error handler
$klein->onHttpError(function ($code, $router) {
    $errorInfo = '';
    $lastError = error_get_last();
    if (isset($lastError) && $lastError != '') {
        foreach ($lastError as $key => $value) {
            $errorInfo .= ' # ' . $key . ' - ' . $value;
        }
    }
    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $objUtils = new \Esqueleto\Classes\Utils();
    $userIP = $objUtils->getUserIP();
    $forwardedFor = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '';
    $messageLog = ' ' . $code . ' _ URL: ' . $url . ' _ ' . $errorInfo . ' _ From: ' . $userIP . ' _ ' . $forwardedFor;

    $log = new Logger('log');
    $log->pushHandler(new StreamHandler(LOG_FILE_ERROR, Logger::ERROR));
    $log->addError($messageLog);

    $home = new Esqueleto\Controller\HomeController();
    $home->errorAction($code);
});

$klein->dispatch();
