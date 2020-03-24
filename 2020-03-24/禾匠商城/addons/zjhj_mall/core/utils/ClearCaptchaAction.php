<?php

namespace app\utils;

use yii\captcha\CaptchaAction;

class ClearCaptchaAction extends CaptchaAction
{
    public function __construct($id, $controller, $config = [])
    {
        $this->minLength = 4;
        $this->maxLength = 4;
        $this->padding = 2;
        $this->offset = 2;
        $this->fontFile = '@app/web/statics/font/MarkerFelt.ttc';
        parent::__construct($id, $controller, $config);
    }

    public function generateVerifyCode()
    {
        if ($this->minLength > $this->maxLength) {
            $this->maxLength = $this->minLength;
        }
        $length = mt_rand($this->minLength, $this->maxLength);

        $letters = '2345678bcefhjkmnprsuvwxyz';
        $code = '';
        $max = strlen($letters) - 1;
        for ($i = 0; $i < $length; ++$i) {
            $code .= $letters[mt_rand(0, $max)];
        }

        return mt_rand(0, 1) ? $code : strtoupper($code);
    }

    public function validate($input, $caseSensitive)
    {
        // 测试环境下忽略验证码
        if(YII_ENV_DEV || YII_ENV_TEST) {
            return true;
        }
        return parent::validate($input, $caseSensitive);
    }
}
