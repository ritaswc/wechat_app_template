<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/17
 * Time: 10:58
 */

namespace app\modules\api\models;

class CartSubmitForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $cart_id_list;

    public function rules()
    {
        return [
            [['cart_id_list'], 'required', 'msg' => '请选择购买的商品'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        return [
            'code' => 0,
        ];
    }
}
