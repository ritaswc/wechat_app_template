<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/28
 * Time: 16:24
 */

namespace app\modules\api\models;

use app\models\Cart;
use app\models\common\api\CommonShoppingList;

class CartDeleteForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $cart_id_list;

    public function rules()
    {
        return [
            [['cart_id_list'], 'required'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        try {
            $this->cart_id_list = json_decode($this->cart_id_list, true);
            $data = [];
            foreach ($this->cart_id_list as $cart_id) {
                $cart = Cart::findOne([
                    'store_id' => $this->store_id,
                    'is_delete' => 0,
                    'user_id' => $this->user_id,
                    'id' => $cart_id,
                ]);
                if (!$cart) {
                    continue;
                }
                $cart->is_delete = 1;
                $cart->save();

                $data[] = [
                    'user_id' => $this->user_id,
                    'good_id' => $cart->goods_id,
                ];
            }
            $wechatAccessToken = $this->wechat->getAccessToken();
            $res = CommonShoppingList::destroyCartGood($wechatAccessToken, $data, $this->store_id);
            return [
                'code' => 0,
                'msg' => '删除完成',
            ];

        } catch (\Exception  $e) {
        }
    }
}
