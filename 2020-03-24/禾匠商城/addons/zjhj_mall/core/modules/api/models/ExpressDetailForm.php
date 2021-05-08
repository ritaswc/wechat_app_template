<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/21
 * Time: 18:00
 */

namespace app\modules\api\models;

use app\models\Goods;
use app\models\IntegralOrderDetail;

class ExpressDetailForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $type;
    public $order_id;

    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['type'], 'string'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        switch ($this->type) {
            case 'IN':
                $orderClass = 'app\models\IntegralOrder';
                $detailClass = 'app\models\IntegralOrderDetail';
                break;
            default:
                $orderClass = 'app\models\Order';
                $detailClass = 'app\models\OrderDetail';
                break;
        }

        $order = $orderClass::findOne([
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
        $od = $detailClass::findOne([
            'order_id' => $order->id,
            'is_delete' => 0,
        ]);
        if ($od) {
            if ($this->type == 'mall') {
                $res['data']['goods_pic'] = Goods::getGoodsPicStatic($od->goods_id)->pic_url;
            } elseif ($this->type == 'IN') {
                $res['data']['goods_pic'] = $od->goods->cover_pic;
            } else {
                $res['data']['goods_pic'] = '';
            }
        } else {
            $res['data']['goods_pic'] = '';
        }
        return $res;
    }
}
