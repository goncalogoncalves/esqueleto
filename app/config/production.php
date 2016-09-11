<?php

error_reporting(0);
ini_set('display_errors', 0);

define('APP_DEBUG', false);

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'esqueleto');

use Devgo\Database as Database;

// connect to db
$database = new Database();

// general configs
try {
    $database->query('SELECT * FROM definition WHERE definition_id = :id_definition');
    $database->bind(':id_definition', '1');
    $rows = $database->resultset();
    foreach ($rows as $key => $value) {
        $configWebsiteName = $value['website_name'];
        $configWebsiteDescription = $value['website_description'];
        $configWebsiteKeywords = $value['website_keywords'];
        $configWebsiteUrl = $value['website_url'];
        //$configEmailAdministrator = $value['email_administrator'];
    }
} catch (Exception $e) {
    $configWebsiteName = 'Devgo';
    $configWebsiteDescription = '';
    $configWebsiteKeywords = '';
    $configWebsiteUrl = '';
    //$configEmailAdministrator = "";
}
$configEmailAdministrator = 'devgoncalo@gmail.com';

if (!isset($_SESSION['website_logged_user_email'])) {
    $_SESSION['website_logged_user_email'] = '';
}
if (!isset($_SESSION['website_logged_user_password'])) {
    $_SESSION['website_logged_user_password'] = '';
}

// static strings that will appear allways
$_SESSION['WEBSITE_HTML_PRE_TITLE'] = 'Esqueleto - ';
$_SESSION['WEBSITE_PRE_DESCRIPTION'] = '';
$_SESSION['WEBSITE_PRE_KEYWORDS'] = 'esqueleto';
$_SESSION['WEBSITE_HTML_POS_TITLE'] = '';
$_SESSION['WEBSITE_POS_DESCRIPTION'] = '';
$_SESSION['WEBSITE_POS_KEYWORDS'] = '';

$_SESSION['WEBSITE_HTML_TITLE'] = $_SESSION['WEBSITE_HTML_PRE_TITLE'].$configWebsiteName.$_SESSION['WEBSITE_HTML_POS_TITLE'];
$_SESSION['WEBSITE_DESCRIPTION'] = $_SESSION['WEBSITE_PRE_DESCRIPTION'].$configWebsiteDescription.$_SESSION['WEBSITE_POS_DESCRIPTION'];
$_SESSION['WEBSITE_KEYWORDS'] = $_SESSION['WEBSITE_PRE_KEYWORDS'].$configWebsiteKeywords.$_SESSION['WEBSITE_POS_KEYWORDS'];
$_SESSION['WEBSITE_NAME'] = $configWebsiteName;
$_SESSION['WEBSITE_EMAIL'] = $configEmailAdministrator;

$_SESSION['WEBSITE_AUTHOR_NAME'] = 'Devgo';
$_SESSION['WEBSITE_AUTHOR_EMAIL'] = 'devgoncalo@gmail.com';
$_SESSION['WEBSITE_LANGUAGE'] = 'pt';

define('COMPANY_NAME', 'Esqueleto');
define('COMPANY_EMAIL', 'devgoncalo@gmail.com');
define('COMPANY_LOGO', 'http://esqueleto.dev/images/Esqueleto logo 220px.png'); // absolute for emails
define('BASE_URL', 'http://esqueleto.dev');
define('RELATIVE_URL', $_SERVER['DOCUMENT_ROOT'].'/');

define('WEBSITE_LOGO', 'http://esqueleto.dev/images/logo.png');

define('MODELS_PATH', APP_PATH.'/app/src/esqueleto/model/');
define('VIEWS_PATH', APP_PATH.'/app/src/esqueleto/view/');
define('LAYOUT_TOP_PATH', VIEWS_PATH.'layout/top.phtml');
define('LAYOUT_BOTTOM_PATH', VIEWS_PATH.'layout/bottom.phtml');
define('LAYOUT_TOPNOINDEX_PATH', VIEWS_PATH.'layout/top-noindex.phtml');
define('LAYOUT_BOTTOMNOINDEX_PATH', VIEWS_PATH.'layout/bottom.phtml');

define('TRANSLATIONS_FILE', APP_PATH.'/app/language/translations.php');
define('LOG_FILE', APP_PATH.'/data/website.log');
define('LOG_VISITS_FILE', APP_PATH.'/data/website_visits.log');

// language
if (isset($_GET['lang'])) {
    $langUrl = strip_tags($_GET['lang']);

    $database->query('SELECT * FROM language WHERE abbreviation = :abbreviation');
    $database->bind(':abbreviation', $langUrl);
    $row = $database->single();
    $langId = $row['lang_id'];
    $lang = $row['abbreviation'];
    $_SESSION['LANG'] = $langUrl;
    $_SESSION['LANG_ID'] = $langId;
}

if (!isset($_SESSION['LANG'])) {
    $_SESSION['LANG'] = 'en';
    $_SESSION['LANG_ID'] = 2;
}

// get and define routes
$database->query('SELECT * FROM route WHERE (lang_id = :lang_id OR generic = :generic )AND valid = :valid');
$database->bind(':lang_id', $_SESSION['LANG_ID']);
$database->bind(':generic', 1);
$database->bind(':valid', 1);
$rows = $database->resultset();
foreach ($rows as $key => $value) {
    foreach ($value as $key => $value) {
        if ($key == 'route_id') {
            $routeId = $value;
        }
        if ($key == 'name') {
            $routeName = strtoupper($value);
        }
        if ($key == 'value') {
            $routeValue = $value;
        }
    }
    $_SESSION["$routeName"] = $routeValue;
}

define('COOKIE_RUNTIME', 1209600);
define('COOKIE_DOMAIN', '.esqueleto.dev/');

// close db connection
$database = null;
