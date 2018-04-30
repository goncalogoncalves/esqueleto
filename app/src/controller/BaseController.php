<?php

namespace Esqueleto\Controller;

use Esqueleto\Classes\Utils;

/**
 * Class responsible to implement methods that help other controllers.
 * App controllers should extend this class.
 */
class BaseController
{

    public function render($filename, $title = null, $data = null)
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestUri = explode("?", $requestUri);
        $data['currentPath'] = $requestUri[0];

        if ($title != null) {
            $_SESSION['WEBSITE_HTML_TITLE'] = $_SESSION['WEBSITE_HTML_PRE_TITLE'] . $_SESSION['WEBSITE_NAME'] . ' - ' . $title;
        }

        // allows to send variables to the view
        ob_get_clean();
        ob_start();
        extract($data);

        // if we want a layout or not
        if (isset($data['layout'])) {
            if ($data['layout'] == false) {
                include_once VIEWS_PATH . $filename;
                die;
            }
        }

        include_once LAYOUT_TOP_PATH;
        include_once VIEWS_PATH . $filename;
        include_once LAYOUT_BOTTOM_PATH;

        ob_end_flush();
        exit();
    }

    public function getUserIP()
    {
        $utils = new Utils();
        $userIP = $utils->getUserIP();

        return isset($userIP) ? $userIP : "";
    }

}
