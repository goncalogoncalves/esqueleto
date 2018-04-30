<?php

if (APP_MODE == "production") {
	error_reporting(0);
	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
	define('APP_DEBUG', false);

	define('DB_HOST', '');
	define('DB_USER', '');
	define('DB_PASS', '');
	define('DB_NAME', '');
} else {
	error_reporting(-1); // shows all
	//error_reporting (E_ALL ^ E_NOTICE); // notices doesnt appear
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	define('APP_DEBUG', true);

	define('DB_HOST', 'localhost');
	define('DB_USER', 'root');
	define('DB_PASS', '');
	define('DB_NAME', 'esqueleto');
}

use \Esqueleto\Classes\Database;

// connect to db
$database = new Database();

// general configs
try {
	$database->query('SELECT * FROM definition WHERE definition_id = :id_definition');
	$database->bind(':id_definition', '1');
	$definitions = $database->resultset();

	$_SESSION['WEBSITE_NAME'] = isset($definitions[0]["website_name"]) ? $definitions[0]["website_name"] : "";
	$_SESSION['WEBSITE_DESCRIPTION'] = isset($definitions[0]["website_description"]) ? $definitions[0]["website_description"] : "";
	$_SESSION['WEBSITE_KEYWORDS'] = isset($definitions[0]["website_keywords"]) ? $definitions[0]["website_keywords"] : "";
	$_SESSION['WEBSITE_URL'] = isset($definitions[0]["website_url"]) ? $definitions[0]["website_url"] : "";
	$_SESSION['WEBSITE_EMAIL_ADMINISTRATOR'] = isset($definitions[0]["website_email_administrator"]) ? $definitions[0]["website_email_administrator"] : "";
	$_SESSION['VIDEO'] = isset($definitions[0]["video"]) ? $definitions[0]["video"] : "";
	$_SESSION['FACEBOOK_URL'] = isset($definitions[0]["facebook_url"]) ? $definitions[0]["facebook_url"] : "";
	$_SESSION['TWITTER_URL'] = isset($definitions[0]["twitter_url"]) ? $definitions[0]["twitter_url"] : "";
	$_SESSION['LOCAL_LATITUDE'] = isset($definitions[0]["local_latitude"]) ? $definitions[0]["local_latitude"] : "";
	$_SESSION['LOCAL_LONGITUDE'] = isset($definitions[0]["local_longitude"]) ? $definitions[0]["local_longitude"] : "";
	$_SESSION['COMPANY_NAME'] = isset($definitions[0]["company_name"]) ? $definitions[0]["company_name"] : "";
	$_SESSION['COMPANY_ABBREVIATION'] = isset($definitions[0]["company_abbreviation"]) ? $definitions[0]["company_abbreviation"] : "";
	$_SESSION['COMPANY_ADDRESS'] = isset($definitions[0]["company_address"]) ? $definitions[0]["company_address"] : "";
	$_SESSION['COMPANY_POSTAL_CODE'] = isset($definitions[0]["company_postal_code"]) ? $definitions[0]["company_postal_code"] : "";
	$_SESSION['COMPANY_LOCALE'] = isset($definitions[0]["company_locale"]) ? $definitions[0]["company_locale"] : "";
	$_SESSION['COMPANY_COUNTRY'] = isset($definitions[0]["company_country"]) ? $definitions[0]["company_country"] : "";
	$_SESSION['COMPANY_EMAIL'] = isset($definitions[0]["company_email"]) ? $definitions[0]["company_email"] : "";
	$_SESSION['COMPANY_EMAIL_COMERCIAL'] = isset($definitions[0]["company_email_comercial"]) ? $definitions[0]["company_email_comercial"] : "";
	$_SESSION['COMPANY_NIF'] = isset($definitions[0]["company_nif"]) ? $definitions[0]["company_nif"] : "";
	$_SESSION['COMPANY_NIB'] = isset($definitions[0]["company_nib"]) ? $definitions[0]["company_nib"] : "";
	$_SESSION['COMPANY_TELEPHONE'] = isset($definitions[0]["company_telephone"]) ? $definitions[0]["company_telephone"] : "";
	$_SESSION['COMPANY_FAX'] = isset($definitions[0]["company_fax"]) ? $definitions[0]["company_fax"] : "";
	$_SESSION['ANNIVERSARY_INTERVAL_DAYS'] = isset($definitions[0]["anniversary_interval_days"]) ? $definitions[0]["anniversary_interval_days"] : "";
	$_SESSION['SALESMAN_COMMISSION'] = isset($definitions[0]["salesman_commission"]) ? $definitions[0]["salesman_commission"] : "";

	$_SESSION['WEBSITE_HTML_PRE_TITLE'] = isset($definitions[0]["website_html_pre_title"]) ? $definitions[0]["website_html_pre_title"] : '';
	$_SESSION['WEBSITE_HTML_POS_TITLE'] = isset($definitions[0]["website_html_pos_title"]) ? $definitions[0]["website_html_pos_title"] : '';
	$_SESSION['WEBSITE_PRE_DESCRIPTION'] = isset($definitions[0]["website_pre_description"]) ? $definitions[0]["website_pre_description"] : '';
	$_SESSION['WEBSITE_POS_DESCRIPTION'] = isset($definitions[0]["website_pos_description"]) ? $definitions[0]["website_pos_description"] : '';
	$_SESSION['WEBSITE_PRE_KEYWORDS'] = isset($definitions[0]["website_pre_keywords"]) ? $definitions[0]["website_pre_keywords"] : '';
	$_SESSION['WEBSITE_POS_KEYWORDS'] = isset($definitions[0]["website_pos_keywords"]) ? $definitions[0]["website_pos_keywords"] : '';

	$_SESSION['WEBSITE_HTML_TITLE'] = $_SESSION['WEBSITE_HTML_PRE_TITLE'] . $_SESSION['WEBSITE_NAME'] . $_SESSION['WEBSITE_HTML_POS_TITLE'];
	$_SESSION['WEBSITE_DESCRIPTION'] = $_SESSION['WEBSITE_PRE_DESCRIPTION'] . $_SESSION['WEBSITE_DESCRIPTION'] . $_SESSION['WEBSITE_POS_DESCRIPTION'];
	$_SESSION['WEBSITE_KEYWORDS'] = $_SESSION['WEBSITE_PRE_KEYWORDS'] . $_SESSION['WEBSITE_KEYWORDS'] . $_SESSION['WEBSITE_POS_KEYWORDS'];

} catch (Exception $e) {

}
$configEmailAdministrator = 'devgoncalo@gmail.com';

if (!isset($_SESSION['USER_ID'])) {
	$_SESSION['USER_ID'] = '';
}
if (!isset($_SESSION['USER_EMAIL'])) {
	$_SESSION['USER_EMAIL'] = '';
}
if (!isset($_SESSION['USER_PASSWORD'])) {
	$_SESSION['USER_PASSWORD'] = '';
}

if (APP_MODE == "production") {
	define('BASE_URL', '');
	define('UPLOADS_DIRECTORY', '');
	define('BASE_URL_GOIZI', '');
	define('RELATIVE_URL_GOIZI', '');
	define('BASE_URL_GOIZI_API', '');
	define('COMPANY_LOGO', '');
	define('IMAGE_NO_IMAGE', '');
} else {
	define('BASE_URL', 'http://esqueleto.localhost');
	define('UPLOADS_DIRECTORY', 'http://esqueleto.localhost/uploads/');
	define('BASE_URL_GOIZI', 'http://esqueleto.localhost/');
	define('RELATIVE_URL_GOIZI', 'C:/wamp64/www/esqueleto/goizi/');
	define('BASE_URL_GOIZI_API', 'http://goizi.localhost/api/');
	define('COMPANY_LOGO', 'http://esqueleto.localhost/img/logo.png'); // absolute for emails
	define('IMAGE_NO_IMAGE', 'http://esqueleto.localhost/img/no-image.png');
}

define('COMPANY_NAME', 'ESQUELETO');
define('COMPANY_EMAIL', 'devgoncalo@gmail.com');
define('COMPANY_DEV_NAME', 'Gonçalo Gonçalves');
define('COMPANY_DEV_EMAIL', 'devgoncalo@gmail.com');

define('RELATIVE_URL', $_SERVER['DOCUMENT_ROOT'] . '/');
define('UPLOADS_DIRECTORY_LOCAL', $_SERVER['DOCUMENT_ROOT'] . '/uploads/');

define('WEBSITE_INDEX', '1');

define('MODELS_PATH', APP_PATH . '/app/src/model/');
define('VIEWS_PATH', APP_PATH . '/app/src/view/');
define('LAYOUT_TOP_PATH', VIEWS_PATH . 'layout/top.phtml');
define('LAYOUT_BOTTOM_PATH', VIEWS_PATH . 'layout/bottom.phtml');

define('TRANSLATIONS_PATH', APP_PATH . '/app/language/');
define('LOG_FILE', APP_PATH . '/app/log/app.log');
define('LOG_FILE_ACTION', APP_PATH . '/app/log/action.log');
define('LOG_FILE_ERROR', APP_PATH . '/app/log/error.log');

if (APP_MODE == "production") {
	define('WEBSITE_LOGO', '');
} else {
	define('WEBSITE_LOGO', 'http://esqueleto.localhost/img/logo.png');
}

// language
if (isset($_GET['lang'])) {
	$langUrl = strip_tags($_GET['lang']);

	$database->query('SELECT * FROM language WHERE abbreviation = :abbreviation');
	$database->bind(':abbreviation', $langUrl);
	$row = $database->single();
	$_SESSION['LANG'] = isset($lang['abbreviation']) ? mb_strtolower($lang['abbreviation']) : "en";
	$_SESSION['LANG_ID'] = isset($lang['lang_id']) ? $lang['lang_id'] : 2;
}

if (!isset($_SESSION['LANG'])) {
	$_SESSION['LANG'] = 'en';
	$_SESSION['LANG_ID'] = 2;
}

// get and define routes
$database->query('SELECT * FROM route WHERE (lang_id = :lang_id OR generic = :generic ) AND valid = :valid');
$database->bind(':lang_id', $_SESSION['LANG_ID']);
$database->bind(':generic', 1);
$database->bind(':valid', 1);
$routes = $database->resultset();
$_SESSION["routes"] = json_encode($routes);
for ($i = 0; $i < sizeof($routes); $i++) {
	$routeId = $routes[$i]["route_id"];
	$routeName = strtoupper($routes[$i]["name"]);
	$routeValue = $routes[$i]["value"];

	if ($routeValue != "") {
		$_SESSION["$routeName"] = $routeValue;
	}
}

define('COOKIE_RUNTIME', 1209600);
define('COOKIE_DOMAIN', '.esqueleto.dev/');

// close db connection
$database = null;
