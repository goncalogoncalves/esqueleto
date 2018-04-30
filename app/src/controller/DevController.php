<?php

namespace Esqueleto\Controller;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class DevController extends BaseController
{
    public function indexAction()
    {
        $dev = 'qweqwe';

        $log = new Logger('log');
        $log->pushHandler(new StreamHandler(LOG_FILE_ACTION, Logger::INFO))->info('Home/index - ' . $this->getUserIP() . ' - ' . $_SESSION['USER_EMAIL']);

        $this->render('dev/index.php', 'Dev', array(
            'dev' => $dev,
        ));
    }

    public function testAction()
    {
        $dev = 'teste';

        $this->render('dev/index.php', 'Dev', array(
            'dev' => $dev,
        ));
    }
}
