<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/29
 * Time: 9:55
 */

namespace app\models;

class CardSendForm extends Model
{
    public $store_id;
    public $user_id;
    public $order_id;

    public function save()
    {
        $goods_list = OrderDetail::findAll(['order_id' => $this->order_id]);
        $card_list = [];
        foreach ($goods_list as $value) {
            $count = 0;
            $card = Goods::getGoodsCard($value['goods_id']);
            while ($count < $value['num']) {
                $card_list = array_merge($card_list, $card);
                $count++;
            }
        }
        foreach ($card_list as $index => $value) {
            $user_card = new UserCard();
            $user_card->store_id = $this->store_id;
            $user_card->user_id = $this->user_id;
            $user_card->card_id = $value['card_id'];
            $user_card->card_name = $value['name'];
            $user_card->card_pic_url = $value['pic_url'];
            $user_card->card_content = $value['content'];
            $user_card->clerk_id = 0;
            $user_card->shop_id = 0;
            $user_card->is_use = 0;
            $user_card->is_delete = 0;
            $user_card->addtime = time();
            $user_card->order_id = $this->order_id;
            $user_card->goods_id = $value['goods_id'];
            $user_card->save();
        }
    }
}
