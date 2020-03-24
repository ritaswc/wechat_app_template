<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/24
 * Time: 18:42
 */

namespace app\modules\mch\models\miaosha;

use app\models\common\api\CommonShoppingList;
use app\models\Express;
use app\models\FormId;
use app\models\Goods;
use app\models\MsOrder;
use app\models\MsWechatTplMsgSender;
use app\models\OrderDetail;
use app\models\Store;
use app\models\User;
use app\models\WechatTplMsgSender;
use app\modules\mch\models\MchModel;
use app\utils\TaskCreate;

class OrderSendForm extends MchModel
{
    public $store_id;
    public $order_id;
    public $express;
    public $express_no;
    public $words;

    public function rules()
    {
        return [
            [['express', 'express_no', 'words'], 'trim'],
            [['express', 'express_no',], 'required', 'on' => 'EXPRESS'],
            [['order_id'], 'required'],
            [['express', 'express_no',], 'string',],
            [['express', 'express_no',], 'default', 'value' => ''],
        ];
    }

    public function attributeLabels()
    {
        return [
            'express' => '快递公司',
            'express_no' => '快递单号',
            'words' => '商家留言',
        ];
    }

    public function batch($arrCSV)
    {
        $empty = [];  //是否存在
        $error = [];   //操作失败
        $cancel = [];  //是否取消
        $offline = []; //到店自提
        $send = [];  //是否发货
        $success = []; //是否成功

        foreach ($arrCSV as $v) {
            $order = MsOrder::findOne([
                'is_delete' => 0,
                'store_id' => $this->store_id,
                'order_no' => $v[1],
            ]);

            if (!$order) {
                $empty[] = $v[1];
                continue;
            }
            if ($order->is_cancel) {
                $cancel[] = $v[1];
                continue;
            }
            if ($order->is_send) {
                $send[] = $v[1];
                continue;
            }
            if ($order->is_pay == 0 && $order->pay_type != 2) {
                $pay[] = $v[1];
            }
            if ($order->is_offline) {
                $offline[] = $v[1];
                continue;
            }
            $order->express_no = $v[2];
            $order->is_send = 1;
            $order->send_time = time();
            $order->express = $this->express;

            if (!$order->save()) {
                $error[] = $v[1];
            } else {
                $success[] = $v[1];
                try {
                    $wechat_tpl_meg_sender = new WechatTplMsgSender($this->store_id, $order->id, $this->getWechat());
                    $wechat_tpl_meg_sender->sendMsg();
                    TaskCreate::orderConfirm($order->id, 'MIAOSHA');
                } catch (\Exception $e) {
                    \Yii::warning($e->getMessage());
                }
            }
        };
        $data = [];
        $max = max(count($empty), count($error), count($cancel), count($send), count($offline), count($pay), count($success));
        for ($i = 0, $k = 0; $i < $max; $k++, $i++) {
            $data[$k][] = $empty[$k];
            $data[$k][] = $cancel[$k];
            $data[$k][] = $send[$k];
            $data[$k][] = $offline[$k];
            $data[$k][] = $pay[$k];
            $data[$k][] = $error[$k];
            $data[$k][] = $success[$k];
        }

        return $data;
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $order = MsOrder::findOne([
            'is_delete' => 0,
            'store_id' => $this->store_id,
            'id' => $this->order_id,
        ]);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在或已删除',
            ];
        }
        if ($order->is_pay == 0 && $order->pay_type != 2) {
            return [
                'code' => 1,
                'msg' => '订单未支付'
            ];
        }

        if ($order->apply_delete == 1) {
            return [
                'code' => 1,
                'msg' => '该订单正在申请取消操作，请先处理'
            ];
        }
//        if ($order->is_send == 1) {
//            return [
//                'code' => 1,
//                'msg' => '该订单已发货',
//            ];
//        }
        $exportList = Express::getExpressList();
        $ok = false;
        foreach ($exportList as $value) {
            if ($value['name'] == $this->express) {
                $ok = true;
                break;
            }
        }
        if (!$ok && $this->scenario == "EXPRESS") {
            return [
                'code' => 1,
                'msg' => '快递公司不正确'
            ];
        }
        $order->express = $this->express;
        $order->express_no = $this->express_no;
        $order->words = $this->words;
        $order->is_send = 1;
        $order->send_time = time();
        if ($order->save()) {
            try {
                $wechat_tpl_meg_sender = new MsWechatTplMsgSender($this->store_id, $order->id, $this->getWechat());
                $wechat_tpl_meg_sender->sendMsg();
            } catch (\Exception $e) {
                \Yii::warning($e->getMessage());
            }
            // 创建订单自动收货定时任务
            TaskCreate::orderConfirm($order->id, 'MIAOSHA');
            $wechatAccessToken = $this->getWechat()->getAccessToken();
            $res = CommonShoppingList::updateBuyGood($wechatAccessToken, $order, 1, 4);
            return [
                'code' => 0,
                'msg' => '发货成功',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '操作失败',
            ];
        }
    }

    /**
     * @deprecated 已废弃
     */
    private function sendMessage($order)
    {
        $user = User::findOne($order->user_id);
        if (!$user) {
            return;
        }
        /* @var FormId $form_id */
        $form_id = FormId::find()->where(['order_no' => $order->order_no])->orderBy('addtime DESC')->one();
        $wechat = $this->getWechat();
        if (!$wechat) {
            return;
        }
        if (!$form_id) {
            return;
        }
        $store = Store::findOne($this->store_id);
        if (!$store || !$store->order_send_tpl) {
            return;
        }

        $goods_list = OrderDetail::find()
            ->select('g.name,od.num')
            ->alias('od')->leftJoin(['g' => Goods::tableName()], 'od.goods_id=g.id')
            ->where(['od.order_id' => $order->id, 'od.is_delete' => 0])->asArray()->all();

        $msg_title = '';
        foreach ($goods_list as $goods) {
            $msg_title .= $goods['name'];
        }


        $access_token = $this->wechat->getAccessToken();
        $api = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token={$access_token}";
        $data = (object)[
            'touser' => $user->wechat_open_id,
            'template_id' => $store->order_send_tpl,
            'form_id' => $form_id->form_id,
            'page' => 'pages/order/order?status=2',
            'data' => (object)[
                'keyword1' => (object)[
                    'value' => $msg_title,
                    'color' => '#333333',
                ],
                'keyword2' => (object)[
                    'value' => $order->express,
                    'color' => '#333333',
                ],
                'keyword3' => (object)[
                    'value' => $order->express_no,
                    'color' => '#333333',
                ],
            ],
        ];
        $data = \Yii::$app->serializer->encode($data);
        $wechat->curl->post($api, $data);
        $res = json_decode($wechat->curl->response, true);
        if (!empty($res['errcode']) && $res['errcode'] != 0) {
            \Yii::warning("模板消息发送失败：\r\ndata=>{$data}\r\nresponse=>" . \Yii::$app->serializer->encode($res));
        }
    }
}
