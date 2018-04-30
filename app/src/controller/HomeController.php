<?php

namespace Esqueleto\Controller;

use Esqueleto\Classes\Utils;
use Esqueleto\Model\HomeModel;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Base class of the app. Responsible to manage index, error page etc.
 */
class HomeController extends BaseController
{

    public function indexAction()
    {
        $log = new Logger('log');
        $log->pushHandler(new StreamHandler(LOG_FILE_ACTION, Logger::INFO))->info('Home/index - ' . $this->getUserIP() . ' - ' . $_SESSION['USER_EMAIL']);

        $this->render(
            'home/index.php',
            'Home',
            array()
        );
    }

    public function genericRouteAction()
    {
        $routes = json_decode($_SESSION["routes"]);
        $route = $_SERVER["REQUEST_URI"];
        $finalTarget = '';
        $arrayInfo = array(
            'content' => "Page not found.",
        );

        for ($i = 0; $i < sizeof($routes); $i++) {

            $tempRouteId = $routes[$i]->route_id;
            $tempName = $routes[$i]->name;
            $tempValue = $routes[$i]->value;
            $tempLangId = $routes[$i]->lang_id;
            $tempValid = $routes[$i]->valid;
            $tempGeneric = $routes[$i]->generic;
            $tempTarget = $routes[$i]->target;

            if ($route == $tempValue) {
                $finalTarget = $tempTarget;
            }
        }

        if ($finalTarget != "") {

            $finalTarget = explode(":", $finalTarget);
            $targetController = $finalTarget[0];
            $targetAction = $finalTarget[1];
            $targetId = $finalTarget[2];

            switch ($targetController) {
                case 'page':
                    if ($targetAction == "view") {
                        $page = new PageController();
                        $page->viewAction($targetId, "");
                    }
                    break;

                default:
                    break;
            }
        }
    }

    public function getMenuAction($parameters = null)
    {
        $utils = new Utils();
        $isAjax = $utils->isAjax();

        if ($isAjax) {
            if ($parameters != null) {
                $homeModel = new HomeModel();

                $type = isset($parameters["type"]) ? $parameters["type"] : "";
                $element = isset($parameters["element"]) ? $parameters["element"] : "";
                $id = isset($parameters["id"]) ? $parameters["id"] : "";

                if ($id != "") {
                    $menu = $homeModel->getMenuById($id);
                } else if ($type != "") {
                    $menu = $homeModel->getMenuByType($type);
                }

                echo json_encode(array(
                    'menuHtml' => $menu,
                ));
            }
        }
    }

    /**
     * Responsible for the error page of the app.
     */
    public function javascriptErrorAction($parameters)
    {
        $utils = new Utils();
        $isAjax = $utils->isAjax();

        if ($isAjax) {
            $txtParameters = '';
            foreach ($parameters as $key => $value) {
                $txtParameters .= $key . ' : ' . $value . "<br />\r\n";
            }

            $userIP = $utils->getUserIP();
            $forwardedFor = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '';
            $messageLog = ' ' . $txtParameters . ' _ From: ' . $userIP . ' _ ' . $forwardedFor;

            $log = new Logger('log');
            $log->pushHandler(new StreamHandler(LOG_FILE_ERROR, Logger::ERROR));
            $log->addError('Javascript -> ' . $messageLog);

            echo json_encode('reported');
        }
    }

    /**
     * Responsible for the error page of the app.
     */
    public function errorAction($errorType)
    {
        //todo: manage code, 404 will also appear here

        $homeModel = new HomeModel();
        $state = $homeModel->reportError($errorType);

        $errorText = 'An unexpected error has occurred.<br /><br />' . $errorType;

        $this->render('home/error.php', 'Error', array(
            'errorText' => $errorText,
        ));
    }

}
