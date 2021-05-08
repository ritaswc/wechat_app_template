<?php

namespace app\models;

use Alipay\AlipayRequestFactory;
use app\models\alipay\MpConfig;
use luweiss\wechat\Wechat;
use app\models\alipay\TplMsgForm;

class LoNoticeSender
{
    public $wechat;
    public $store_id;
    public $order_id;
    public $order;
    public $user;
    public $form_id;
    public $wechat_template_message;

    /**
     * PtNoticeSender constructor.
     * @param Wechat $wechat
     * @param integer $store_id
     */
    public function __construct($wechat, $store_id)
    {

        $this->wechat = $wechat;
        $this->store_id = $store_id;
        $this->wechat_template_message = WechatTemplateMessage::findOne(['store_id' => $this->store_id]);  
    }

    /**
     * 发送中奖模板消息
     * @param double $refund_price
     */
    public function sendSucNotice($ids)
    {
        $tpl_id = Option::get('lottery_success_notice', $this->store_id, 'lottery','');
        $tpl_id_alipay = TplMsgForm::get($this->store_id)->pt_fail_notice;

        $order_list = LotteryLog::find()->alias('lo')
            ->select('lo.*,u.wechat_open_id,u.nickname,g.name as gname')
            ->leftJoin(['u' => User::tableName()], 'lo.user_id=u.id')
            ->leftJoin(['g' => Goods::tableName()], 'lo.goods_id=g.id')
            ->where(['in','lo.id',$ids])
            ->asArray()
            ->all();
        if (!$order_list) {
            \Yii::warning('模板消息发送失败，订单不存在');
            return false;
        }

        foreach ($order_list as $order) {
            if(!$order['form_id']==''){
                $order['form_id'] = LotteryLog::find()->select('form_id')->where(['store_id' => $this->store_id,'user_id' => $order['user_id'],'lottery_id' => $order['lottery_id']])->column()[0];
            }

            if (!$order['form_id']) {
                $order['form_id'] = LotteryLog::find()->select('form_id')
                ->where(['store_id' => $this->store_id,'child_id' => 0,'user_id' => $order['user_id'],'lottery_id' => $order['lottery_id']])->column()[0];
            }

            if (!$order['form_id']) {
                \Yii::warning("抽奖记录(id={$order['id']})未发送模板消息，form_id不存在");
                continue;
            }

            $platform = User::findOne(['id' => $order['user_id']])->platform;
            $is_alipay = $platform == 1;
            
            if($is_alipay){
                $this->wechat_template_message = TplMsgForm::get($this->store_id);
            }

            $name = $order['gname'];
            $data = [
                'touser' => $order['wechat_open_id'],
                'template_id' => $is_alipay ? $tpl_id_alipay : $tpl_id,
                'page' => 'lottery/detail/detail?id='.$order['id'],
                'form_id' => $order['form_id'],
                'data' => [
                    'keyword1' => [
                        'value' => '恭喜中奖',
                        'color' => '#555555',
                    ],
                    'keyword2' => [
                        'value' => $name,
                        'color' => '#555555',
                    ],
                ],
            ];

           $this->sendTplMsg($data, $is_alipay);

        }
        return true;
    }

    private function sendTplMsg($data, $is_alipay)
    {
        if ($is_alipay) {     
            $config = MpConfig::get($this->store_id);
            $aop = $config->getClient();
            $request = AlipayRequestFactory::create('alipay.open.app.mini.templatemessage.send', [
                'biz_content' => [
                    'to_user_id' => $data['touser'],
                    'form_id' => $data['form_id'],
                    'user_template_id' => $data['template_id'],
                    'page' => $data['page'],
                    'data' => $data['data'],
                ],
            ]);
            $response = $aop->execute($request);

            if ($response->isSuccess() === false) {
                \Yii::warning("模板消息发送失败：\r\ndata=>{$data}\r\nresponse=>" . json_encode($response->getError(), JSON_UNESCAPED_UNICODE));
            }
        } else {
            $access_token = $this->wechat->getAccessToken();
            $api = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token={$access_token}";
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
            $this->wechat->curl->post($api, $data);
            $res = json_decode($this->wechat->curl->response, true);

            if (!empty($res['errcode']) && $res['errcode'] != 0) {

                \Yii::warning("模板消息发送失败：\r\ndata=>{$data}\r\nresponse=>" . json_encode($res, JSON_UNESCAPED_UNICODE));
            }
        }
    }
}
