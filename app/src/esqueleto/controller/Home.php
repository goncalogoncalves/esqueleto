<?php

namespace Devgo\Controller;

use Devgo\BaseController;
use Devgo\Utils;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


/**
 * Base class of the app. Responsible to manage index, error pageetc.
 */
class Home extends BaseController
{
    /**
     * Responsible for the index of the app.
     */
    public function indexAction()
    {

        $pageTitle = "Index";
        $pageContent = "Test index app";

        $this->render('home/index.php', array(
            'pageTitle' => $pageTitle,
            'pageContent' => $pageContent,
        ));
    }


    /**
     * Responsible for the error page of the app.
     */
    public function javascriptErrorAction($parameters)
    {
        $utilsObj = new Utils();
        $isAjax = $utilsObj->isAjax();

        if ($isAjax) {
            $isAjax = 'ok';

            $txtParameters = '';
            foreach ($parameters as $key => $value) {
                $txtParameters .= $key.' : '.$value."<br />\r\n";
            }

            $objUtils = new Utils();
            $userIP = $objUtils->getUserIP();
            $forwardedFor = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '';
            $messageLog = 'From '.$userIP.' _ '.$forwardedFor.' // Error: '.$txtParameters;

            $log = new Logger('log');
            $log->pushHandler(new StreamHandler(LOG_FILE, Logger::ERROR));
            $log->addError('javascript -> '.$messageLog);

            echo json_encode('reported');
        } else {
            $isAjax = 'notok';
        }
    }


    /**
     * Responsible for the error page of the app.
     */
    public function errorAction($errorType)
    {
        //todo: manage code, 404 will also appear here

        $this->render('home/error.php', array(
            'errorText' => $errorType,
            ));
    }
}
