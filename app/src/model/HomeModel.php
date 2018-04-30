<?php

namespace Esqueleto\Model;

use Esqueleto\Classes\Utils;

/**
 * Model of the controller Home.
 */
class HomeModel extends BaseModel
{

    public function getMenuByType($type)
    {
        $this->db->query('SELECT * FROM view_menu WHERE menu_type_id = :menu_type_id AND lang_id = :lang_id');
        $this->db->bind(':menu_type_id', $type);
        $this->db->bind(':lang_id', $_SESSION['LANG_ID']);
        $row = $this->db->single();
        $menuContent = json_decode(json_decode($row['content']));

        return $menuContent;
    }

    public function getMenuById($id)
    {
        $this->db->query('SELECT * FROM view_menu WHERE menu_id = :menu_id AND lang_id = :lang_id');
        $this->db->bind(':menu_id', $id);
        $this->db->bind(':lang_id', $_SESSION['LANG_ID']);
        $row = $this->db->single();
        $menuContent = json_decode(json_decode($row['content']));

        return $menuContent;
    }

    public function reportError($errorType)
    {
        $objUtils = new Utils();
        $userIp = $objUtils->getUserIP();

        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

        $requestUri = $_SERVER['REQUEST_URI'];
        $requestUri = explode("?", $requestUri);
        $local = $requestUri[0];

        $message = '';

        if ($_SESSION['USER_EMAIL'] != '') {
            $recordCreatedBy = $_SESSION['USER_EMAIL'];
        } else {
            $recordCreatedBy = 'not_logged_in';
        }

        $this->db->query('INSERT INTO log_frontend (message, error, referer, local, user_ip, record_created_by) VALUES (:message, :error, :referer, :local, :user_ip, :record_created_by)');
        $this->db->bind(':message', $message);
        $this->db->bind(':error', $errorType);
        $this->db->bind(':referer', $referer);
        $this->db->bind(':local', $local);
        $this->db->bind(':user_ip', $userIp);
        $this->db->bind(':record_created_by', $recordCreatedBy);
        $this->db->execute();
        $lastId = $this->db->lastInsertId();

        $lastError = error_get_last();
        $lastErrorType = isset($lastError["type"]) ? $lastError["type"] : "";
        $lastErrorMessage = isset($lastError["message"]) ? $lastError["message"] : "";
        $lastErrorFile = isset($lastError["file"]) ? $lastError["file"] : "";
        $lastErrorLine = isset($lastError["line"]) ? $lastError["line"] : "";

        $lastErrorHtml = $lastErrorMessage . '<br>';
        $lastErrorHtml .= $lastErrorFile . '<br>';
        $lastErrorHtml .= $lastErrorLine . '<br>';

        $message = '<table cellpadding="0" cellspacing="0">';
        $message .= '<tr><td width="100px;">Erro</td><td><b>' . $errorType . '</b></td></tr>';
        $message .= '<tr><td>Local</td><td><b>' . $local . '</b></td></tr>';
        $message .= '<tr><td>Referer</td><td><b>' . $referer . '</b></td></tr>';
        $message .= '<tr><td>User</td><td><b>' . $recordCreatedBy . '</b></td></tr>';
        $message .= '<tr><td>User IP</td><td><b>' . $userIp . '</b></td></tr>';
        $message .= '<tr><td>Log ID</td><td><b>' . $lastId . '</b></td></tr>';
        $message .= '<tr><td>Last error</td><td><b>' . $lastErrorHtml . '</b></td></tr>';
        $message .= '</table>';

        //$objEmail = new Email();
        //$emailSent = $objEmail->sendEmail(COMPANY_NAME, COMPANY_EMAIL, COMPANY_DEV_NAME, COMPANY_DEV_EMAIL, 'LOG WEBSITE PAPELARTE', $message);

        return true;
    }

}
