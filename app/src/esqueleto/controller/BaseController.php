<?php

namespace Devgo;

/**
 * Class responsible to implement methods that help other controllers.
 * App controllers should extend this class.
 */
class BaseController
{
    public $db = null;

    public function __construct()
    {
        $this->openDatabaseConnection();

    }

    /**
     * Responsible for creating a database instanace.
     */
    private function openDatabaseConnection()
    {
        if ($this->db == null) {
            $this->db = new Database();
        }
    }

    /**
     * Responsible for loading a modelName.
     *
     * @param string $modelName The name of the file of the model
     *
     * @return object Model instance
     */
    public function loadModel($modelName)
    {
        require_once MODELS_PATH . $modelName . '.php';

        return new $modelName($this->db);
    }

    /**
     * Responsible for rendering the view.
     *
     * @param string $filename the file to render
     * @param array  $data     variables to pass to the view
     *
     * @return require the proper files
     */
    public function render($filename, $data = null)
    {
        require_once TRANSLATIONS_FILE;

        $requestUri = $_SERVER['REQUEST_URI'];
        $requestUri = explode("?", $requestUri);
        $data['currentPath'] = $requestUri[0];

        $data = $this->getMenus($data);

        // allows to send variables to the view
        ob_get_clean();
        ob_start();
        extract($data);

        // if we want a layout or not
        if (isset($data['layout'])) {
            if ($data['layout'] == false) {
                require_once VIEWS_PATH.$filename;
            }else if($data['layout'] == "noindex") {
                require_once LAYOUT_TOPNOINDEX_PATH;
                require_once VIEWS_PATH.$filename;
                require_once LAYOUT_BOTTOMNOINDEX_PATH;
            } else {
                require_once LAYOUT_TOP_PATH;
                require_once VIEWS_PATH.$filename;
                require_once LAYOUT_BOTTOM_PATH;
            }
        } else {
            require_once LAYOUT_TOP_PATH;
            require_once VIEWS_PATH.$filename;
            require_once LAYOUT_BOTTOM_PATH;
        }
    }

    public function getMenus($data)
    {
        // get main menu
        /*$this->db->query('SELECT * FROM menu');
        $row = $this->db->single();
        $menuHtmlMain = json_decode(json_decode($row['content']));*/

        if ($_SESSION['LANG'] == "en") {
            $linkHome = '<a href="/">Home</a>';
            $linkTestPage = '<a href="/page/1/test-page">Page</a>';
        }else if ($_SESSION['LANG'] == "pt") {
            $linkHome = '<a href="/">Inicio</a>';
            $linkTestPage = '<a href="/pagina/2/pagina-teste">Pagina</a>';
        }

        $menuHtmlMain = '';
        $menuHtmlMain .= '<ul>';
        $menuHtmlMain .= '<li>'. $linkHome .'</li>';
        $menuHtmlMain .= '<li>'. $linkTestPage .'</li>';
        $menuHtmlMain .= '</ul>';

        $data['menuHtmlMain'] = $menuHtmlMain;

        return $data;
    }

}
