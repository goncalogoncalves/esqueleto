<?php

use Devgo\Email;

/**
 * Model of the controller Home.
 */
class HomeModel
{
    public $db = null;

    /**
     * Prepare access to the db.
     */
    public function __construct($db)
    {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit('Database connection could not be established.');
        }
    }

    /**
     * Responsible for the setup of the general seo.
     */
    public function setupGeneralSEO($arrayInfo)
    {
        $_SESSION['WEBSITE_HTML_TITLE'] = $_SESSION['WEBSITE_HTML_PRE_TITLE'].$arrayInfo['seo_title'].$_SESSION['WEBSITE_HTML_POS_TITLE'];
        $_SESSION['WEBSITE_DESCRIPTION'] = $_SESSION['WEBSITE_PRE_DESCRIPTION'].$arrayInfo['seo_description'].$_SESSION['WEBSITE_POS_DESCRIPTION'];
        $_SESSION['WEBSITE_KEYWORDS'] = $_SESSION['WEBSITE_PRE_KEYWORDS'].','.$arrayInfo['seo_keywords'].','.$_SESSION['WEBSITE_POS_KEYWORDS'];
    }

}
