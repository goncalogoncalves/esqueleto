<?php

namespace Devgo;

/**
 * Captcha
 * Class responsible to create and validate a captcha
 *
 * Created by Gonçalo Gonçalves - devgoncalo@gmail.com
 *
 */
class Captcha
{

    public $devMode          = true;
    public $captchaCode      = "";
    public $imageWidth       = 180;
    public $imageHeight      = 50;
    public $charNumber       = 6;
    public $arrayFonts       = array('claredon.ttf', 'courier_bold.ttf', 'toledo.ttf','valken.ttf', 'impact.ttf');
    //public $possibleChars  = '23456789ABCDEFGHKLMNPQRSTUVYWXZ';
    //public $possibleChars  = '1234567890';
    public $possibleChars    = 'ABCDEFGHKLMNPQRSTUVYWXZ';
    public $fontPath         = "";
    public $addDots          = true;
    public $addLines         = false;
    public $addRectangles    = false;
    public $addCircles       = false;
    public $numberDots       = 3000;
    public $numberLines      = 10;
    public $numberRectangles = 20;
    public $numberCircles    = 30;

    /**
     * Construct
     * @param string $fontPath the path of the fonts
     */
    public function __construct($fontPath = "fonts/")
    {
        $this->fontPath = $fontPath;
    }

    /**
     * Create a new captcha
     * @return string html of the captcha
     */
    public function createCaptcha()
    {
        ob_start();

        $temp_code = '';
        $i = 0;
        // create temp code
        while ($i < $this->charNumber) {
            // get random letter
            $temp_code .= substr($this->possibleChars, mt_rand(0, strlen($this->possibleChars)-1), 1);
            $i++;
        }

        //header('Content-Type: image/png');
        $image = imagecreatetruecolor($this->imageWidth, $this->imageHeight);
        $colorWhite = imagecolorallocate($image, 255, 255, 255);

        // background rectangle
        imagefilledrectangle($image, 0, 0, $this->imageWidth, $this->imageHeight, $colorWhite);

        // add dots
        if ($this->addDots) {
            for ($c = 0; $c < $this->numberDots; $c++) {
                $colorGrey = rand(0, 200);
                $colorDot  = imagecolorallocate($image, $colorGrey, $colorGrey, $colorGrey);
                $x = rand(0, $this->imageWidth-1);
                $y = rand(0, $this->imageHeight-1);
                imagesetpixel($image, $x, $y, $colorDot);
            }
        }

        // add lines
        if ($this->addLines) {
            for ($c = 0; $c < $this->numberLines; $c++) {
                $randomColor = strtoupper(dechex(rand(0, 10000000)));
                $x    = rand(0, $this->imageWidth-1);
                $y    = rand(0, $this->imageHeight-1);
                $x2 = rand(0, $this->imageWidth-1);
                $y2 = rand(0, $this->imageHeight-1);
                imageline($image, $x, $y, $x2, $y2, $randomColor);
            }
        }

        // add rectangles
        if ($this->addRectangles) {
            for ($c = 0; $c < $this->numberRectangles; $c++) {
                $randomColor = strtoupper(dechex(rand(0, 10000000)));
                $x    = rand(0, $this->imageWidth-1);
                $y    = rand(0, $this->imageHeight-1);
                $x2 = rand(0, $this->imageWidth-1);
                $y2 = rand(0, $this->imageHeight-1);
                imagerectangle($image, $x, $y, $x2, $y2, $randomColor);
            }
        }

        // add circles
        if ($this->addCircles) {
            for ($c = 0; $c < $this->numberCircles; $c++) {
                $colorGrey   = rand(0, 200);
                $colorCircle = imagecolorallocate($image, $colorGrey, $colorGrey, $colorGrey);
                $x    = rand(0, $this->imageWidth-1);
                $y    = rand(0, $this->imageHeight-1);
                $circleSize = rand(3, 6);
                imagefilledellipse($image, $x, $y, $circleSize, $circleSize, $colorCircle);
            }
        }

        // add text, letter by letter
        for ($c = 0; $c < $this->charNumber; $c++) {
            $colorGrey  = rand(0, 100);
            $colorText  = imagecolorallocate($image, $colorGrey, $colorGrey, $colorGrey);
            $font       = $this->fontPath.$this->arrayFonts[rand(0, sizeof($this->arrayFonts)-1)];
            $angle      = rand(-20, 20);
            $letterSize = rand(20, 28);
            $x = $c*25+15;
            $y = rand(30, 40);
            $letter = substr($this->possibleChars, mt_rand(0, strlen($this->possibleChars)-1), 1);

            // bonus
            /*switch ($c) {
                case 0: $letter = "C"; break;
                case 1: $letter = "O"; break;
                case 2: $letter = "D"; break;
                case 3: $letter = "E"; break;
                case 4: $letter = "1"; break;
                case 5: $letter = "2"; break;
                case 6: $letter = "3"; break;
            }*/

            imagettftext($image, $letterSize, $angle, $x, $y, $colorText, $font, $letter);
            $this->captchaCode = $this->captchaCode.$letter;
        }

        imagepng($image);
        imagedestroy($image);
        $finalImage = ob_get_clean();

        session_start();
        $_SESSION['CAPTCHA_CODE'] = $this->captchaCode;

        $htmlCaptchaImage = "<img alt='Captcha' title='Captcha' src='data:image/jpeg;base64," . base64_encode($finalImage)."'>";

        $htmlCaptcha = '<table class="captcha-table">';
        if (isset($_GET['captcha_state']) && $_GET['captcha_state'] == "false") {
            $htmlCaptcha .= '<tr><td class="captcha-wrong">Wrong code!</td></tr>';
        }
        $htmlCaptcha .= '
		<tr>
			<td>'.$htmlCaptchaImage.'
				<div class="captcha-bar-tools">
					<a href="javascript:createCaptcha();" title="Change image"><img src="img/refresh.png" alt="create new captcha"></a>
				</div>
			</td>
		</tr>
		<tr>
			<td><input id="user-input-captcha" name="user-input-captcha" class="captcha-input"></td>
		</tr>';

        if ($this->devMode == true) {
            $htmlCaptcha .= '<tr><td>'.$this->captchaCode.'</td></tr>';
        }

        $htmlCaptcha .= '</table>';

        return $htmlCaptcha;
    }

    /**
     * Validate captcha code
     * @param  string $value the string inserted by the user
     * @return boolean       if code is correct
     */
    public function validateCaptcha($value)
    {
        $validCode = false;
        session_start();
        $captchaCode = $_SESSION['CAPTCHA_CODE'];

        if ($captchaCode == $value) {
            $validCode = true;
        }

        return $validCode;
    }
}
