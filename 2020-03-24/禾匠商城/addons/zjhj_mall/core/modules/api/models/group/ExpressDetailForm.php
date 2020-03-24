<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/21
 * Time: 18:00
 */

namespace app\modules\api\models\group;

use app\models\Goods;
use app\models\Order;
use app\models\OrderDetail;
use app\models\PtGoods;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\modules\api\models\ApiModel;
use yii\helpers\VarDumper;

class ExpressDetailForm extends ApiModel
{
    public $store_id;
    public $user_id;

    public $order_id;

    public function rules()
    {
        return [
            [['order_id'], 'required'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $order = PtOrder::findOne([
            'store_id' => $this->store_id,
            'id' => $this->order_id,
        ]);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在',
            ];
        }

        $model = new \app\models\ExpressDetailForm();
        $model->express = $order->express;
        $model->express_no = $order->express_no;
        $model->store_id = $this->store_id;
        $res = $model->search();
        if ($res['code'] != 0) {
            $res['code'] = 0;
            if (!$res['data']) {
                $res['data'] = [];
            }
            $res['data']['status'] = 0;
            $res['data']['status_text'] = '未知';
        }
        $res['data']['express'] = $order->express;
        $res['data']['express_no'] = $order->express_no;
        $od = PtOrderDetail::findOne([
            'order_id' => $order->id,
            'is_delete' => 0,
        ]);
        if ($od) {
            $res['data']['goods_pic'] = PtGoods::getGoodsPicStatic($od->goods_id);
        } else {
            $res['data']['goods_pic'] = '';
        }
        return $res;
    }
}
