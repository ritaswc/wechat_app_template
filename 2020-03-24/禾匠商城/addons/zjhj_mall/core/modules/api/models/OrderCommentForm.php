<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/16
 * Time: 10:11
 */

namespace app\modules\api\models;

use app\models\InOrderComment;
use app\models\IntegralOrder;
use app\models\IntegralOrderDetail;
use yii\helpers\Html;

class OrderCommentForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $order_id;
    public $goods_list;
    public $type;
    public function rules()
    {
        return [
            [['goods_list', 'order_id'], 'required'],
            [['type'], 'string'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        if ($this->type == 'mall') {
            $orderClass = 'app\models\Order';
            $commentClass = 'app\models\OrderComment';
            $detailClass = 'app\models\OrderDetail';
        }
        if ($this->type == 'IN') {
            $orderClass = 'app\models\IntegralOrder';
            $commentClass = 'app\models\InOrderComment';
            $detailClass = 'app\models\IntegralOrderDetail';
        }

        $order = $orderClass::findOne([
            'id' => $this->order_id,
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'is_delete' => 0,
        ]);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在或已删除',
            ];
        }

        $goods_list = json_decode($this->goods_list);
        if (!$goods_list) {
            return [
                'code' => 1,
                'msg' => '商品信息不能为空',
            ];
        }

        $t = \Yii::$app->db->beginTransaction();
        foreach ($goods_list as $goods) {
            $order_detail = $detailClass::findOne([
                'id' => $goods->order_detail_id,
                'order_id' => $order->id,
                'goods_id' => $goods->goods_id,
                'is_delete' => 0,
            ]);
            if (!$order_detail) {
                continue;
            }

            $order_comment = new $commentClass();
            $order_comment->store_id = $this->store_id;
            $order_comment->order_detail_id = $order_detail->id;
            $order_comment->user_id = $this->user_id;
            $order_comment->order_id = $this->order_id;
            $order_comment->goods_id = $order_detail->goods_id;
            $order_comment->score = $goods->score;
            $order_comment->content = Html::encode($goods->content);
            //$order_comment->content = mb_convert_encoding($order_comment->content, 'UTF-8');
            $order_comment->content = preg_replace('/[\xf0-\xf7].{3}/', '', $order_comment->content);
            $pic_list = [];
            foreach ($goods->uploaded_pic_list as $pic) {
                $pic_list[] = Html::encode($pic);
            }
            $order_comment->pic_list = json_encode($pic_list, JSON_UNESCAPED_UNICODE);
            $order_comment->addtime = time();
            if (!$order_comment->save()) {
                $t->rollBack();
                return $this->getErrorResponse($order_comment);
            }
        }
        $order->is_comment = 1;
        if ($order->save()) {
            $t->commit();
            return [
                'code' => 0,
                'msg' => '提交成功',
                'type' => $this->type,
            ];
        } else {
            $t->rollBack();
            return $this->getErrorResponse($order);
        }
    }
}
