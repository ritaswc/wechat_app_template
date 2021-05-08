<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/7/19
 * Time: 9:31
 */

namespace app\modules\api\models\bargain;


use app\hejiang\ApiResponse;
use app\models\BargainGoods;
use app\models\BargainOrder;
use app\models\Goods;
use app\models\Order;
use app\models\OrderDetail;
use app\modules\api\models\ApiModel;

class BargainSubmitForm extends ApiModel
{
    public $store;
    public $user;
    public $goods_id;

    public function rules()
    {
        return [
            [['goods_id'], 'integer']
        ];
    }

    public function save()
    {
        if(!$this->validate()){
            return $this->getErrorResponse();
        }
        /**
         * @var \app\models\Goods $goods
         * @var \app\models\BargainGoods $bargain
         */
        $goods = BargainGoods::getIdToGoods($this->goods_id, $this->store);
        if(!$goods) {
            return new ApiResponse(1, '商品不存在', []);
        }
        $bargain = $goods->bargain;
        if(time() < $bargain->begin_time || time() > $bargain->end_time){
            return new ApiResponse(1, '砍价活动未开始', []);
        }
        $bargainOrder = BargainOrder::getUserOrder($this->user->id, $this->goods_id, $this->store->id);
        if($bargainOrder && (time() - $bargainOrder->addtime) < $bargainOrder->time * 3600){
            return new ApiResponse(0,'跳转页面',[
                'order_id'=>$bargainOrder->id
            ]);
        }
        $goodsNum = OrderDetail::find()->alias('od')->innerJoin(['o' => Order::tableName()], 'o.id=od.order_id')
            ->where([
                'od.goods_id' => $goods->id, 'store_id' => $this->user->store_id, 'o.user_id' => $this->user->id,
                'o.is_cancel' => 0, 'o.is_delete' => 0, 'o.is_show' => 1
            ])->select('SUM(od.num) num')->scalar();
        if($goods->confine_count && $goods->confine_count > 0 && $goodsNum >= $goods->confine_count){
            return new ApiResponse(1,'超出商品购买数量',[]);
        }
        $attr = $this->getAttr($goods);
        $attr_id_list = [];
        foreach ($attr as $_a) {
            array_push($attr_id_list, $_a['attr_id']);
        }
        $attr_info = $goods->getAttrInfo($attr_id_list);
        if ($attr_info['num'] < 1) { //库存不足
            return new ApiResponse(1,'库存不足',[]);
        }

        if($bargain->end_time < time()){
            return new ApiResponse(1,'砍价已结束',[]);
        }
        $bargainOrder = new BargainOrder();
        $bargainOrder->store_id = $this->store->id;
        $bargainOrder->user_id = $this->user->id;
        $bargainOrder->goods_id = $this->goods_id;
        $bargainOrder->order_no = $this->getOrderNo();
        $bargainOrder->original_price = $goods->price;
        $bargainOrder->min_price = $bargain->min_price;
        $bargainOrder->time = $bargain->time;
        $bargainOrder->status = 0;
        $bargainOrder->status_data = $bargain->status_data;
        $bargainOrder->attr = \Yii::$app->serializer->encode($attr);
        $bargainOrder->is_delete = 0;
        $bargainOrder->addtime = time();
        if ($bargainOrder->save()) {
            // 库存减少
            $goods->numSub([$attr[0]['attr_id']],1);

            return new ApiResponse(0, '提交成功', [
                'order_id' => $bargainOrder->id
            ]);
        } else {
            return $this->getErrorResponse($bargainOrder);
        }

    }


    public function getOrderNo()
    {
        $order_no = null;
        while (true) {
            $order_no = date('YmdHis') . mt_rand(100000, 999999);
            $exist_order_no = Order::find()->where(['order_no' => $order_no])->exists();
            if (!$exist_order_no)
                break;
        }
        return $order_no;
    }

    private function getAttr($goods)
    {
        /**
         * @var \app\models\Goods $goods
         */
        $attrGroupList = $goods->getAttrGroupList();
        $attrList = $attrGroupList[0]->attr_list;

        $newAttr[] = [
            'attr_id'=>$attrList[0]->attr_id,
            'attr_name'=>$attrList[0]->attr_name,
            'attr_group_id'=>$attrGroupList[0]->attr_group_id,
            'attr_group_name'=>$attrGroupList[0]->attr_group_name,
        ];

        return $newAttr;
    }
}