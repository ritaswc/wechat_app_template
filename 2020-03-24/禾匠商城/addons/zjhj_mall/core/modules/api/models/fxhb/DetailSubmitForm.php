<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/2/8
 * Time: 10:52
 */

namespace app\modules\api\models\fxhb;

use Alipay\AlipayRequestFactory;
use app\models\alipay\MpConfig;
use app\models\alipay\TplMsgForm;
use app\models\Coupon;
use app\models\FxhbHongbao;
use app\models\FxhbSetting;
use app\models\User;
use app\models\UserCoupon;
use app\modules\api\models\ApiModel;

class DetailSubmitForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $id;
    public $form_id;

    /** @var FxhbSetting $setting */
    private $setting;

    public function rules()
    {
        return [
            [['id', 'form_id',], 'required'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $setting = FxhbSetting::findOne(['store_id' => $this->store_id]);
        if (!$setting) {
            return [
                'code' => 1,
                'msg' => '活动尚未开启。',
                'game_open' => 0,
            ];
        }
        $this->setting = $setting;
        if ($setting->game_open != 1) {
            return [
                'code' => 1,
                'msg' => '活动已结束。',
                'game_open' => 0,
            ];
        }

        $hongbao = FxhbHongbao::find()
            ->alias('fh')
            ->select('fh.*,u.nickname,u.avatar_url')
            ->leftJoin(['u' => User::tableName()], 'fh.user_id=u.id')
            ->where([
                'fh.store_id' => $this->store_id,
                'fh.id' => $this->id,
                'fh.parent_id' => 0,
            ])->asArray()->one();

        if (!$hongbao) {
            return [
                'code' => 1,
                'msg' => '红包不存在',
                'game_open' => 0,
            ];
        }

        if ($hongbao['is_expire'] == 1) {
            return [
                'code' => 1,
                'msg' => '该红包已过期',
                'game_open' => 1,
                'reload' => 1,
            ];
        }

        //时间过期
        if ($hongbao['is_expire'] == 0 && $hongbao['is_finish'] == 0 && time() > $hongbao['expire_time']) {
            FxhbHongbao::updateAll(['is_expire' => 1], ['id' => $hongbao['id']]);
            return [
                'code' => 1,
                'msg' => '该红包已过期',
                'game_open' => 1,
                'reload' => 1,
            ];
        }

        if ($hongbao['is_finish'] == 1) {
            return [
                'code' => 1,
                'msg' => '红包已被瓜分完了哦~',
                'game_open' => 1,
                'reload' => 1,
            ];
        }

        if ($hongbao['user_id'] == $this->user_id) {
            return [
                'code' => 1,
                'msg' => '您已拆过该红包~',
                'game_open' => 1,
                'reload' => 0,
            ];
        }
        if ($this->getIsOpenParentHongbao($hongbao)) {
            return [
                'code' => 1,
                'msg' => '您已帮他拆过红包~',
                'game_open' => 1,
                'reload' => 0,
            ];
        }

        $model = new FxhbHongbao();
        $model->parent_id = $this->id;
        $model->store_id = $this->store_id;
        $model->user_id = $this->user_id;
        $model->user_num = $hongbao['user_num'];
        $model->coupon_total_money = $hongbao['coupon_total_money'];
        $model->coupon_money = $hongbao['coupon_money'];
        $model->coupon_use_minimum = $hongbao['coupon_use_minimum'];
        $model->coupon_expire = $hongbao['coupon_expire'];
        $model->distribute_type = $hongbao['distribute_type'];
        $model->is_expire = 0;
        $model->expire_time = $hongbao['expire_time'];
        $model->is_finish = 0;
        $model->addtime = time();
        $model->form_id = $this->form_id;
        if (!$model->save()) {
            $res = $this->getErrorResponse($model);
            $res['game_open'] = 1;
            $res['reload'] = 0;
        }
        $this->checkHongbaoFinish($hongbao);
        return [
            'code' => 0,
            'msg' => '恭喜您拆红包成功！',
            'game_open' => 1,
            'reload' => 1,
        ];
    }

    /**
     * 是否帮助该用户拆过红包
     */
    private function getIsOpenParentHongbao($hongbao)
    {
        $exist = FxhbHongbao::find()->where([
            'user_id' => $hongbao['user_id'],
            'id' => FxhbHongbao::find()->select('parent_id')->where([
                'AND',
                ['user_id' => $this->user_id],
                ['!=', 'parent_id', 0],
            ]),
            'is_expire' => 0,
        ])->exists();
        return $exist ? true : false;
    }

    /**
     * 检查红包人数是否凑集
     */
    private function checkHongbaoFinish($hongbao)
    {
        $hongbao = FxhbHongbao::findOne($hongbao['id']);
        $sub_hongbao_list = FxhbHongbao::find()->where(['parent_id' => $hongbao->id])->all();
        /** @var FxhbHongbao[] $hongbao_list */
        $hongbao_list = array_merge([$hongbao], $sub_hongbao_list);
        if (count($hongbao_list) != $hongbao->user_num) {
            return;
        }
        if ($hongbao->distribute_type == 0) {
            $rand = [];
            for($i=0;$i<$hongbao->user_num;$i++){
                $rand[] = mt_rand() % 100 +1;
            };
            $sum = array_sum($rand);
            $ratio = []; 
            foreach($rand as $k=>$v){
                if(count($rand)==$k+1){
                    $ratio_sum = array_sum($ratio);
                    $ratio[$k] = round($hongbao['coupon_total_money'] - $ratio_sum,2);
                }else{
                    
                    $ratio[$k] = (float)sprintf('%.2f', $v/$sum * $hongbao['coupon_total_money']); 
                }
            }
            foreach ($hongbao_list as $i => $item) {
                $item->coupon_money = $ratio[$i];
                $item->is_finish = 1;
                $item->save();
                $this->addCoupon([
                    'user_id' => $item->user_id,
                    'coupon_use_minimum' => $item->coupon_use_minimum,
                    'coupon_money' => $item->coupon_money,
                    'coupon_expire' => $item->coupon_expire,
                ]);
                if ($this->setting->tpl_msg_id) {
                    $this->sendWechatMsg([
                        'user_id' => $item->user_id,
                        'coupon_money' => $item->coupon_money,
                        'template_id' => $this->setting->tpl_msg_id,
                        'form_id' => $item->form_id,
                        'id' => $hongbao->id,
                    ]);
                }
            }
        }
        if ($hongbao->distribute_type == 1) {
            //平均分配
            foreach ($hongbao_list as $i => $item) {
                $item->coupon_money = $hongbao->coupon_total_money / count($hongbao_list);
                $item->is_finish = 1;
                $item->save();
                $this->addCoupon([
                    'user_id' => $item->user_id,
                    'coupon_use_minimum' => $item->coupon_use_minimum,
                    'coupon_money' => $item->coupon_money,
                    'coupon_expire' => $item->coupon_expire,
                ]);
                if ($this->setting->tpl_msg_id) {
                    $this->sendWechatMsg([
                        'user_id' => $item->user_id,
                        'coupon_money' => $item->coupon_money,
                        'template_id' => $this->setting->tpl_msg_id,
                        'form_id' => $item->form_id,
                        'id' => $hongbao->id,
                    ]);
                }
            }
        }
    }

    private function addCoupon($args)
    {
        $coupon = new Coupon();
        $coupon->store_id = $this->store_id;
        $coupon->name = '代金券红包';
        $coupon->discount_type = 2;
        $coupon->min_price = $args['coupon_use_minimum'];
        $coupon->sub_price = $args['coupon_money'];
        $coupon->expire_type = 1;
        $coupon->expire_day = $args['coupon_expire'];
        $coupon->addtime = time();
        $coupon->is_delete = 1;
        $coupon->is_join = 1;
        $coupon->save();

        $uc = new UserCoupon();
        $uc->store_id = $this->store_id;
        $uc->user_id = $args['user_id'];
        $uc->coupon_id = $coupon->id;
        $uc->begin_time = time();
        $uc->end_time = time() + $coupon->expire_day * 86400;
        $uc->is_expire = 0;
        $uc->is_use = 0;
        $uc->is_delete = 0;
        $uc->addtime = time();
        $uc->type = 0;
        $uc->save();
    }

    private function getFloatLength($num)
    {
        $count = 0;
        $temp = explode('.', $num);
        if (sizeof($temp) > 1) {
            $decimal = end($temp);
            $count = strlen($decimal);
        }
        return $count;
    }

    /**
     * 生成范围内随机数
     * @param integer|float $min 最小值
     * @param integer|float $max 最大值
     * @param bool $with_float 是否生成小数点
     * @param int $float_length 小数点个数
     * @return float|int
     */
    private function myRand($min, $max, $with_float = false, $float_length = 2)
    {
        if ($with_float) {
            $base = pow(10, $float_length);
            $min = floatval(sprintf('%.' . $float_length . 'f', $min)) * $base;
            $max = floatval(sprintf('%.' . $float_length . 'f', $max)) * $base;
            $res = mt_rand($min, $max);
            $res = $res / $base;
        } else {
            $res = mt_rand($min, $max);
        }
        return $res;
    }

    /**
     * 发送模板消息
     */
    private function sendWechatMsg($args)
    {
        $user = User::findOne(['id' => $args['user_id']]);
        if (!$user || !$user->wechat_open_id) {
            \Yii::warning('模板消息发送失败：用户不存在或openID为空');
            return false;
        }
        $data = [
            'touser' => $user->wechat_open_id,
            'template_id' => $args['template_id'],
            'form_id' => $args['form_id'],
            'page' => 'pages/fxhb/detail/detail?id=' . $args['id'],
            'data' => [
                'keyword1' => [
                    'value' => '您的账户有' . $args['coupon_money'] . '元红包到账',
                    'color' => '#333333',
                ],
                'keyword2' => [
                    'value' => '拆红包活动奖励',
                    'color' => '#333333',
                ],
            ],
        ];
        if(\Yii::$app->fromAlipayApp()){
            $templateId = TplMsgForm::get($user->store_id)->tpl_msg_id;
            $config = MpConfig::get($this->store_id);
            $aop = $config->getClient();
            $request = AlipayRequestFactory::create('alipay.open.app.mini.templatemessage.send', [
                'biz_content' => [
                    'to_user_id' => $data['touser'],
                    'form_id' => $data['form_id'],
                    'user_template_id' => $templateId,
                    'page' => $data['page'],
                    'data' => $data['data'],
                ],
            ]);
            $response = $aop->execute($request);

            if ($response->isSuccess() === false) {
                \Yii::warning("模板消息发送失败：\r\ndata=>{$data}\r\nresponse=>" . json_encode($response->getError(), JSON_UNESCAPED_UNICODE));
            }
        }else{
            $wechat = $this->getWechat();
            if (!$wechat) {
                \Yii::warning('模板消息发送失败：$wechat为空');
                return false;
            }
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
            $access_token = $wechat->getAccessToken();
            $api = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token={$access_token}";
            $curl = $wechat->curl;
            $curl->post($api, $data);
            if ($curl->http_error) {
                return false;
            }
            \Yii::info('模板消息发送结果：' . $curl->response);
            return json_decode($curl->response, true);
        }
    }
}
