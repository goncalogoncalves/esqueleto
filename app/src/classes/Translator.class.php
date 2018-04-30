<?php

namespace Esqueleto\Classes;

class Translator {

    public $activeLang;

    public function __construct($activeLang = null)
    {
        if ($activeLang == null) {
            $activeLang = $_SESSION['LANG'];
        }

        $this->activeLang = $activeLang;
    }

    public function translate($txtToTranslate)
    {
        require TRANSLATIONS_PATH.$this->activeLang.'.php';

        $txtTranslated = $txt[$txtToTranslate];

        return $txtTranslated;
    }

}
