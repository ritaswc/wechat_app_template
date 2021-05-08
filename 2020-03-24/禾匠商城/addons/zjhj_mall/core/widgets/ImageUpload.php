<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\VarDumper;

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/31
 * Time: 15:19
 */
class ImageUpload extends Widget
{
    public $name;
    public $value;
    public $tip;
    public $width = 400;
    public $height = 300;

    public $multiple = false;
    public $max = 0;

    public function init()
    {
        parent::init();
        if (!$this->tip)
            $this->tip = "{$this->width}×{$this->height}";
        if ($this->multiple && $this->value && !is_array($this->value)) {
            $this->value = [$this->value];
        }
    }

    public function run()
    {
        parent::run();
        echo $this->render('image-upload', [
            'name' => $this->name,
            'value' => $this->value,
            'tip' => $this->tip,
            'width' => $this->width,
            'height' => $this->height,

            'multiple' => $this->multiple,
            'max' => $this->max,
        ]);
    }
}