<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/5
 * Time: 16:24
 */

namespace app\modules\mch\models\group;

use app\models\Option;
use app\modules\mch\models\MchModel;

class NoticeForm extends MchModel
{
    public $store_id;
    public $pintuan_success_notice;
    public $pintuan_fail_notice;
    public $pintuan_refund_notice;

    public function rules()
    {
        return [
            [['pintuan_success_notice', 'pintuan_fail_notice', 'pintuan_refund_notice'], 'trim'],
            [['pintuan_success_notice', 'pintuan_fail_notice', 'pintuan_refund_notice'], 'string'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        Option::setList([
            [
                'name' => 'pintuan_success_notice',
                'value' => $this->pintuan_success_notice,
            ],
            [
                'name' => 'pintuan_fail_notice',
                'value' => $this->pintuan_fail_notice,
            ],
            [
                'name' => 'pintuan_refund_notice',
                'value' => $this->pintuan_refund_notice,
            ],
        ], $this->store_id);

        return [
            'code' => 0,
            'msg' => '保存成功',
            'data' => $this->attributes,
        ];
    }

    public function getModel()
    {
        $model = Option::getList(['pintuan_success_notice', 'pintuan_fail_notice', 'pintuan_refund_notice',], $this->store_id);
        return $model;
    }
}
