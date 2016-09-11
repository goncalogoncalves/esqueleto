<?php

namespace Devgo;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Utils
{

    public function logEvent($title = '', $parameters = '')
    {
        if (is_array($parameters)) {
            $newParams = '';
            for ($i=0; $i < sizeOf($parameters); $i++) {
                $newParams .= ' - '.$parameters[$i];
            }
            $parameters = $newParams;
        }
        $userIP = $this->getUserIP();
        $forwardedFor = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '';
        $messageLog = 'From '.$userIP.' _ '.$forwardedFor.' // Parameters: '.$parameters;

        $log = new Logger('log');
        $log->pushHandler(new StreamHandler(LOG_VISITS_FILE, Logger::INFO));
        $log->addInfo($title.$messageLog);
    }
    

    public function getUserIP()
    {
        $client = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : '';
        $forward = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '';
        $remote = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }

    /**
     * Responsible to tell if the request was made through ajax.
     *
     * @return bool True if the request was made through ajax
     */
    public function isAjax()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        }

        return false;
    }


    /**
     * Responsible to format a number.
     *
     * @param float $number the number
     *
     * @return float formated number
     */
    public function formatNumber($number)
    {
        $number = number_format($number, 2, ',', '.');

        return $number;
    }


    /**
     * Responsible to get a string inside other string.
     *
     * @param string $string string that contains what we want
     * @param int    $start  string star position
     * @param int    $end    string end position
     *
     * @return array
     */
    public function getStringBetween($string, $start, $end)
    {
        $string = ' '.$string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        $arrayString = array(substr($string, $ini, $len), $ini, $len);

        return $arrayString;
    }
}
