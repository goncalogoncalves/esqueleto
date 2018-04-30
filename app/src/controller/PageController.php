<?php

namespace Esqueleto\Controller;

use Esqueleto\Classes\Utils;
use Esqueleto\Model\PageModel;

class PageController extends BaseController
{

    public function viewAction($id, $title)
    {
        $pageModel = new PageModel();
        $page = $pageModel->getPageById($id);
        $page = $pageModel->preparePage($page);

        $title = isset($page["title"]) ? $page["title"] : "";

        $pageModel->setupGeneralSEO($page);

        $this->render('page/index.php', $title, array(
            'page' => $page,
        ));
    }

    public function getPageAction($parameters = null)
    {
        $objUtils = new Utils();
        $isAjax = $objUtils->isAjax();

        if ($isAjax) {
            if ($parameters != null) {

                $type = isset($parameters["type"]) ? $parameters["type"] : "";
                $element = isset($parameters["element"]) ? $parameters["element"] : "";
                $id = isset($parameters["id"]) ? $parameters["id"] : "";

                $pageModel = new PageModel();
                if ($id != "") {
                    $page = $pageModel->getPageById($id);
                } else if ($type != "") {
                    $page = $pageModel->getPageByType($type);
                }

                $page = $pageModel->preparePage($page);

                $pageContent = $page["content"];

                echo json_encode(array(
                    'content' => $pageContent,
                ));
            }
        }
    }

}
