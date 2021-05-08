<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/25
 * Time: 19:19
 */

namespace app\modules\api\models;

use app\hejiang\ApiCode;
use app\models\Address;

class AddressSetDefaultForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $address_id;

    public function rules()
    {
        return [
            [['address_id'], 'required'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $address = Address::findOne($this->address_id);
        if (!$address) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg'  => '操作失败，收货地址不存在',
            ];
        }
        Address::updateAll(['is_default' => Address::DEFAULT_STATUS_FALSE], [
            'store_id'  => $this->store_id,
            'user_id'   => $this->user_id,
            'is_delete' => Address::DELETE_STATUS_FALSE,
        ]);
        $address->is_default = Address::DEFAULT_STATUS_TRUE;
        $address->save();
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg'  => '操作成功',
        ];
    }
}
