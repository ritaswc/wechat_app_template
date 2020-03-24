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

class AddressDeleteForm extends ApiModel
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

        $address->is_delete = Address::DELETE_STATUS_TRUE;
        $address->save();
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg'  => '操作成功',
        ];
    }
}
