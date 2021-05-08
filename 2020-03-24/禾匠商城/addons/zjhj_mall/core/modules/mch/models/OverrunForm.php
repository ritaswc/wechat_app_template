<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2019/3/29
 * Time: 15:42
 * @copyright: ©2019 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */

namespace app\modules\mch\models;


use app\hejiang\ApiCode;
use app\models\Option;

class OverrunForm extends MchModel
{
    public $max_picture;
    public $max_diy;
    public $over_picture;
    public $over_diy;

    public function rules()
    {
        return [
            [['max_diy'], 'integer', 'min' => 0],
            [['max_picture'], 'number', 'min' => 0],
            [['over_picture', 'over_diy'], 'string'],
            [['over_picture', 'over_diy'], 'default', 'value' => 0],
            [['max_picture'], 'default', 'value' => 1],
            [['max_diy'], 'default', 'value' => 20],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        Option::set('overrun', [
            'max_picture' => $this->max_picture,
            'max_diy' => $this->max_diy,
            'over_picture' => $this->over_picture,
            'over_diy' => $this->over_diy,
        ], 0, 'admin');

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '保存成功'
        ];
    }

    public function search()
    {
        $data = Option::get('overrun', 0, 'admin');
        if (!$data) {
            $data = [
                'max_picture' => 1,
                'max_diy' => 20,
                'over_picture' => 0,
                'over_diy' => 0,
            ];
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '',
            'data' => $data
        ];
    }
}
