<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/15
 * Time: 16:48
 */

namespace app\modules\mch\models\recharge;

use app\models\Option;
use app\modules\mch\models\MchModel;

class SettingForm extends MchModel
{
    public $store_id;

    public $status;
    public $pic_url;
    public $ad_pic_url;
    public $page_url;
    public $help;
    public $p_pic_url;
    public $type;

    public function rules()
    {
        return [
            [['status', 'pic_url', 'ad_pic_url', 'page_url', 'help', 'p_pic_url','type'], 'trim'],
            [['status', 'pic_url', 'ad_pic_url', 'page_url', 'help', 'p_pic_url','type'], 'string'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $list = [];
        $list['status'] = $this->status;
        $list['pic_url'] = $this->pic_url;
        $list['ad_pic_url'] = $this->ad_pic_url;
        $list['page_url'] = $this->page_url;
        $list['p_pic_url'] = $this->p_pic_url;
        $list['help'] = $this->help;
        $list['type'] = $this->type;
        $list = \Yii::$app->serializer->encode($list);
        Option::set('re_setting', $list, $this->store_id, 'app');
        return [
            'code' => 0,
            'msg' => '成功'
        ];
    }
}
