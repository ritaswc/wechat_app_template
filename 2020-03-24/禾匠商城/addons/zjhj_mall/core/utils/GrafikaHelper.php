<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/20
 * Time: 14:06
 */

namespace app\utils;

class GrafikaHelper
{
    /**
     * 获取支持的图片处理库
     * @return array
     */
    public static function getSupportEditorLib()
    {
        switch (true) {
            case function_exists('gd_info'):
                return ['Gd'];
            case class_exists('\Imagick') && method_exists((new \Imagick()), 'setImageOpacity'):
                return ['Imagick'];
            default:
                return ['Gd'];
        }
    }
}
