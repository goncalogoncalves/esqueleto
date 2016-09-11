<?php

namespace Devgo\Controller;

use Devgo\BaseController;
use Devgo\Utils;

class Page extends BaseController
{
    public function indexAction($id, $title)
    {
        $pageModel = $this->loadModel('PageModel');
        $page      = $pageModel->getPageById($id);

        $homeModel = $this->loadModel('HomeModel');
        $homeModel->setupGeneralSEO($page);

        $pageContent = stripcslashes($page['content']);

        $pageTitle   = $page['title'];
        $pageContent = $pageContent;

        $this->render('page/index.php', array(
            'pageTitle' => $pageTitle,
            'pageContent' => $pageContent
            ));
    }
}
