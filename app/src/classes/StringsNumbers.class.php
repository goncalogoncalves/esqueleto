<?php

namespace Esqueleto\Classes;

class StringsNumbers
{

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

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
