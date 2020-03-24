<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/6
 * Time: 10:44
 */

namespace app\modules\mch\models;

use app\models\Option;

class CopyrightForm extends MchModel
{
    public $data;
    public $store_id;

    public $text;
    public $icon;
    public $url;
    public $open_type;
    public $is_phone;
    public $phone;

    public function rules()
    {
        return [
            [['text', 'icon', 'url', 'open_type', 'is_phone', 'phone'], 'trim'],
            [['text', 'icon', 'url', 'open_type', 'is_phone', 'phone'], 'string'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $_data = $this->data;
        $_data['copyright'] = [
            'text' => $this->text,
            'icon' => $this->icon,
            'url' => $this->url,
            'open_type' => $this->open_type,
            'is_phone' => $this->is_phone,
            'phone' => $this->phone,
        ];
        $_data = \Yii::$app->serializer->encode($_data);
        Option::set('user_center_data', $_data, $this->store_id);
        return [
            'code' => 0,
            'msg' => '保存成功',
        ];
    }
}
