<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8
 * Time: 14:53
 */

namespace app\modules\mch\controllers;

use Alipay\AlipayRequestFactory;
use app\models\Goods;
use app\modules\api\models\ApiModel;
use app\modules\mch\models\ExportList;
use app\utils\Sms;
use app\hejiang\ApiResponse;
use app\hejiang\ValidationErrorResponse;
use app\models\Cash;
use app\models\CashWechatTplSender;
use app\models\Color;
use app\models\Option;
use app\models\Qrcode;
use app\models\Setting;
use app\models\Share;
use app\models\User;
use app\models\WechatTemplateMessage;
use app\models\WechatTplMsgSender;
use app\modules\mch\models\CashForm;
use app\modules\mch\models\OrderListForm;
use app\modules\mch\models\QrcodeForm;
use app\modules\mch\models\ShareBasicForm;
use app\modules\mch\models\ShareCustomForm;
use app\modules\mch\models\ShareGetQrcodeForm;
use app\modules\mch\models\ShareListForm;
use app\modules\mch\models\ShareOrderForm;
use app\modules\mch\models\ShareSettingForm;
use app\modules\mch\models\StoreDataForm;
use yii\helpers\VarDumper;

class ShareController extends Controller
{
    /**
     * @return string
     * 分销商列表
     */
    public function actionIndex()
    {
        $form = new ShareListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        $form->limit = 10;
        $arr = $form->getList();
        $exportList = $form->excelFields();

        $team = $arr['team_list'];
        $count = $form->getCount();
        $setting = Setting::findOne(['store_id' => $this->store->id]);

        return $this->render('index', [
            'list' => $arr['list'],
            'pagination' => $arr['pagination'],
            'setting' => $setting,
            'team' => \Yii::$app->serializer->encode($team),
            'exportList' => \Yii::$app->serializer->encode($exportList),
            'count' => $count
        ]);
    }

//    添加备注
    public function actionSellerComments()
    {
        $user_id = \Yii::$app->request->get('user_id');
        $seller_comments = \Yii::$app->request->get('seller_comments');
        $share = Share::findOne(['store_id' => $this->store->id, 'is_delete' => 0, 'id' => $user_id]);
        $share->seller_comments = $seller_comments;
        if ($share->save()) {
            return [
                'code' => 0,
                'msg' => '成功'
            ];
        } else {
            return new ValidationErrorResponse($share->errors);
        }
    }

    /**
     * @return mixed|string
     * 佣金设置
     */
    public function actionSetting()
    {
        $store_id = $this->store->id;
        $list = Setting::findOne(['store_id' => $store_id]);
        if (!$list) {
            $list = new Setting();
        }
        if (\Yii::$app->request->isPost) {
            $form = new ShareSettingForm();
            $model = \Yii::$app->request->post('model');
            $form->list = $list;
            $form->store_id = $store_id;
            $form->attributes = $model;
            return $form->save();
        }
        foreach ($list as $index => $value) {
            $list[$index] = str_replace("\"", "&quot;", $value);
        }
        return $this->render('setting', [
            'list' => $list
        ]);
    }

    /**
     * 批量设置
     */
    public function actionBatch()
    {
        $get = \Yii::$app->request->get();
        $res = 0;
        $goods_group = $get['goods_group'];
        $goods_id_group = [];
        $goods_user_group = [];
        foreach ($goods_group as $index => $value) {
            if ($get['type'] == 0) {
                array_push($goods_id_group, $value['id']);
                array_push($goods_user_group, $value['user_id']);
            }
        }
        $condition = ['and', ['in', 'id', $goods_id_group], ['store_id' => $this->store->id]];
        $usercondition = ['and', ['in', 'id', $goods_user_group], ['store_id' => $this->store->id]];

        if ($get['type'] == 0) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $res = Share::updateAll(['status' => 1], $condition);
                $user = User::updateAll(['time' => time(), 'is_distributor' => 1], $usercondition);
                $transaction->commit();
            } catch (\Exception $e) {
                // $error = $e->getMessae();
                $transaction->rollback();
            }
        }

        if ($res > 0) {
            return [
                'code' => 0,
                'msg' => 'success'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => 'fail'
            ];
        }
    }

    /**
     * @return mixed|string
     * 基础设置
     */
    public function actionBasic()
    {
        $store_id = $this->store->id;
        $list = Setting::findOne(['store_id' => $store_id]);
        $qrcode = Qrcode::findOne(['store_id' => $store_id, 'is_delete' => 0]);
        if (!$list) {
            $list = new Setting();
        }
        if (!$qrcode) {
            $qrcode = new Qrcode();
        }
        if (\Yii::$app->request->isPost) {
            $model = \Yii::$app->request->post('model');
            $form = new ShareBasicForm();
            $form->list = $list;
            $form->store_id = $store_id;
            $form->attributes = $model;
            return $form->save();
        }
        $goodName = Goods::find()->where(['id' => $list->share_good_id])->select('name')->scalar();
        $option = Option::getList('cash_max_day,auto_share_val,cash_service_charge', $this->store->id, 'share', 0.00);
        return $this->render('basic', [
            'list' => $list,
            'qrcode' => $qrcode,
            'option' => $option,
            'goodName' => $goodName
        ]);
    }

    /**
     * @param int $id
     * @param int $status
     * @return mixed|string
     * 申请审核
     */
    public function actionStatus($id = 0, $status = 1)
    {
        $share = Share::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$share) {
            return [
                'code' => 1,
                'msg' => '网络异常，请刷新重试'
            ];
        }
        if (!in_array($status, [1, 2])) {
            return [
                'code' => 1,
                'msg' => '网络异常，请刷新重试'
            ];
        }
        $share->status = $status;
        if ($status == 1) {
            User::updateAll(['time' => time(), 'is_distributor' => 1], ['id' => $share->user_id, 'store_id' => $this->store->id]);
        } else {
            User::updateAll(['time' => time(), 'is_distributor' => 0], ['id' => $share->user_id, 'store_id' => $this->store->id]);
        }
        if ($share->save()) {
            $tpl = new CashWechatTplSender($this->store->id, $share->id, $this->wechat, 1);
            $tpl->applyMsg();
            return [
                'code' => 0,
                'msg' => '成功'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '请刷新重试'
            ];
        }
    }

    /**
     * @return string
     * 提现列表
     */
    public function actionCash()
    {
        $form = new CashForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->limit = 10;
        $form->fields = \Yii::$app->request->post('fields');
        $form->flag = \Yii::$app->request->post('flag');
        $form->platform = \Yii::$app->request->get('platform');
        $res = $form->getList();
        $count = $form->getCount();
        return $this->render('cash', [
            'list' => $res['list'],
            'pagination' => $res['pagination'],
            'count' => $count,
            'exportList' => \Yii::$app->serializer->encode($res['exportList'])
        ]);
    }

    /**
     * @param int $id
     * @param int $status
     * @return mixed|string
     * 申请审核
     */
    public function actionApply($id = 0, $status = 0)
    {
        $cash = Cash::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$cash) {
            return [
                'code' => 1,
                'msg' => '提现记录不存在，请刷新重试'
            ];
        }
        if ($cash->status >= $status) {
            return [
                'code' => 1,
                'msg' => '提现记录已操作，请刷新重试'
            ];
        }
        $cash_cache = \Yii::$app->cache->get('cash_cache_' . $id);
        if ($cash_cache && $cash_cache == $cash->order_no) {
            return [
                'code' => 1,
                'msg' => '网络繁忙，请刷新重试'
            ];
        }
        if (!$cash->order_no) {
            $order_no = null;
            while (true) {
                $order_no = date('YmdHis') . mt_rand(100000, 999999);
                $exist_order_no = Cash::find()->where(['order_no' => $order_no])->exists();
                if (!$exist_order_no) {
                    break;
                }
            }
            $cash->order_no = $order_no;
            $cash->save();
        }
        \Yii::$app->cache->set('cash_cache_' . $id, $cash->order_no);
        if (!in_array($status, [1, 3])) {
            \Yii::$app->cache->set('cash_cache_' . $id, false);
            return [
                'code' => 1,
                'msg' => '提现记录已审核，请刷新重试'
            ];
        }
        $cash->status = $status;
        if ($status == 3) {
            $user = User::findOne(['id' => $cash->user_id]);
            $user->price += $cash->price;
            if (!$user->save()) {
                \Yii::$app->cache->set('cash_cache_' . $id, false);
                return [
                    'code' => 1,
                    'msg' => '网络异常'
                ];
            }
        }
        \Yii::$app->cache->set('cash_cache_' . $id, false);
        if ($cash->save()) {
            if ($cash->status == 3) {
                $wechat_tpl_meg_sender = new CashWechatTplSender($this->store->id, $cash->id, $this->wechat);
                $wechat_tpl_meg_sender->cashFailMsg();
            }
            return [
                'code' => 0,
                'msg' => '成功'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '网络异常,请刷新重试'
            ];
        }
    }

//    余额提现
    public function actionApplyRemaining($id = 0, $status = 0)
    {
        return 1111111111;
        $cash = Cash::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$cash) {
            return [
                'code' => 1,
                'msg' => '提现记录不存在，请刷新重试'
            ];
        }
        if (!in_array($status, [5])) {
            return [
                'code' => 1,
                'msg' => '提现记录已审核，请刷新重试'
            ];
        }
    }

    /**
     * @param int $id
     * @param int $status
     * @return mixed|string
     * 确认打款
     * 支付未做
     */
    public function actionConfirm($id = 0, $status = 0)
    {
        $cash = Cash::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$cash) {
            return [
                'code' => 1,
                'msg' => '提现记录不存在，请刷新重试'
            ];
        }
        if ($cash->status >= $status) {
            return [
                'code' => 1,
                'msg' => '提现记录已操作，请刷新重试'
            ];
        }
        if ($cash->status == 3) {
            return [
                'code' => 1,
                'msg' => '提现记录已被驳回'
            ];
        }
        $cash_cache = \Yii::$app->cache->get('cash_cache_' . $id);
        if ($cash_cache && $cash_cache == $cash->order_no) {
            return [
                'code' => 1,
                'msg' => '网络繁忙，请刷新重试'
            ];
        }
        if (!$cash->order_no) {
            $order_no = null;
            while (true) {
                $order_no = date('YmdHis') . mt_rand(100000, 999999);
                $exist_order_no = Cash::find()->where(['order_no' => $order_no])->exists();
                if (!$exist_order_no) {
                    break;
                }
            }
            $cash->order_no = $order_no;
            $cash->save();
        }
        \Yii::$app->cache->set('cash_cache_' . $id, $cash->order_no);
        if ($cash->status != 1) {
            \Yii::$app->cache->set('cash_cache_' . $id, false);
            return [
                'code' => 1,
                'msg' => '操作错误，请刷新重试'
            ];
        }
        $price = Cash::getServiceMoney($cash);
        if ($status == 2) {  //微信自动打款
            $cash->status = 2;
            $cash->pay_time = time();
            $cash->pay_type = 1;
            $user = User::findOne(['id' => $cash->user_id]);
            if($user->platform == 0){
                $data = [
                    'partner_trade_no' => $cash->order_no,
                    'openid' => $user->wechat_open_id,
                    'amount' => $price * 100,
                    'desc' => '转账'
                ];
                $res = $this->wechat->pay->transfers($data);
            }else{
                $request = AlipayRequestFactory::create('alipay.fund.trans.toaccount.transfer',[
                    'biz_content' => [
                        'out_biz_no' => $cash->order_no,
                        'payee_type' => 'ALIPAY_USERID',
                        'payee_account' => $user->wechat_open_id,
                        'amount' => $price
                    ]
                ]);
                $aop = ApiModel::getAlipay($cash->store_id);
                try{
                    $res = $aop->execute($request)->getData();\Yii::warning($res);
                }catch(\Exception $e){
                    \Yii::$app->cache->set('cash_cache_' . $id, false);
                    return [
                        'code' => 1,
                        'msg' => $e->getMessage()
                    ];
                }
                if ($res['code'] != 10000) {
                    \Yii::$app->cache->set('cash_cache_' . $id, false);
                    return [
                        'code' => 1,
                        'msg' => $res['sub_msg']
                    ];
                }
                $res['result_code'] = "SUCCESS";
            }
        } elseif ($status == 4) { //手动打款
            $cash->status = 2;
            $cash->pay_time = time();
            $cash->pay_type = 2;
            if ($cash->type == 3) {
                $user = User::findOne(['id' => $cash->user_id]);
                $user->money += doubleval($price);
                if (!$user->save()) {
                    \Yii::$app->cache->set('cash_cache_' . $id, false);
                    foreach ($user->errors as $error) {
                        return [
                            'code' => 1,
                            'msg' => $error
                        ];
                    }
                }
            }
//            $cash->type = 2;
            $res['result_code'] = "SUCCESS";
        }
        \Yii::$app->cache->set('cash_cache_' . $id, false);
        if ($res['result_code'] == 'SUCCESS') {
            $cash->save();
            $wechat_tpl_meg_sender = new CashWechatTplSender($this->store->id, $cash->id, $this->wechat);
            $wechat_tpl_meg_sender->cashMsg();
            return [
                'code' => 0,
                'msg' => '成功'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => $res['err_code_des'],
                'data' => $res
            ];
        }
    }

    /**
     * @return string
     * 设置推广海报
     */
    public function actionQrcode()
    {
        $store_id = $this->store->id;
        $qrcode = Qrcode::findOne(['store_id' => $store_id, 'is_delete' => 0]);
        $color = Color::find()->select('id,color')->andWhere(['is_delete' => 0])->asArray()->all();
        if (!$qrcode) {
            $qrcode = new Qrcode();
        }
        if (\Yii::$app->request->isPost) {
            $form = new QrcodeForm();
            $model = \Yii::$app->request->post('model');
            $form->store_id = $store_id;
            $form->qrcode = $qrcode;
            $form->attributes = $model;
            return $form->save();
        }
        $font_position = json_decode($qrcode->font_position, true);
        $qrcode_position = json_decode($qrcode->qrcode_position, true);
        $avatar_position = json_decode($qrcode->avatar_position, true);
        $avatar_size = json_decode($qrcode->avatar_size, true);
        $qrcode_size = json_decode($qrcode->qrcode_size, true);
        $font_size = json_decode($qrcode->font, true);
        $first = Color::findOne(['id' => $font_size['color']]);
        return $this->render('qrcode', [
            'qrcode' => $qrcode,
            'color' => \Yii::$app->serializer->encode($color),
            'first' => $first->id,
            'font_c' => $first->color,
            'avatar_w' => $avatar_size['w'],
            'avatar_x' => $avatar_position['x'],
            'avatar_y' => $avatar_position['y'],
            'qrcode_w' => $qrcode_size['w'],
            'qrcode_c' => ($qrcode_size['c'] == 'true') ? 1 : 0,
            'qrcode_x' => $qrcode_position['x'],
            'qrcode_y' => $qrcode_position['y'],
            'font_x' => $font_position['x'],
            'font_y' => $font_position['y'],
            'font_w' => $font_size['size'],
        ]);
    }

    /**
     * @param int $id
     * @return mixed|string
     * @throws \yii\db\Exception
     * 删除分销商
     */
    public function actionDel($id = 0)
    {
        $share = Share::findOne(['id' => $id, 'is_delete' => 0]);
        if (!$share) {
            return [
                'code' => 1,
                'msg' => '网络异常'
            ];
        }
        $t = \Yii::$app->db->beginTransaction();
        $count1 = Share::updateAll(['is_delete' => 1], 'id=:id', [':id' => $id]);
        $user = User::findOne($share->user_id);
        $user->is_distributor = 0;
        $user->parent_id = 0;
        $user->time = time();
        $user->save();
        $count3 = User::updateAll(['parent_id' => 0], 'parent_id=:parent_id', [':parent_id' => $share->user_id]);
        if ($count1 != 0) {
            $t->commit();
            return [
                'code' => 0,
                'msg' => '成功'
            ];
        } else {
            $t->rollBack();
            return [
                'code' => 1,
                'msg' => '网络异常'
            ];
        }
    }

    public function actionOrder()
    {
        // 获取可导出数据
        $f = new ExportList();
        $f->order_type = 5;
        $exportList = $f->getList();

        $parent_id = \Yii::$app->request->get('parent_id');
        $form = new ShareOrderForm();
        $form->attributes = \Yii::$app->request->get();
        $form->attributes = \Yii::$app->request->post();
        $form->platform = \Yii::$app->request->get('platform');
        $form->store_id = $this->store->id;
        $form->parent_id = $parent_id ? (int)$parent_id : false;
        $form->limit = 10;
        $data = $form->search();
        if ($parent_id) {
            $user = User::findOne(['store_id' => $this->store->id, 'id' => $parent_id]);
        }
        $setting = Setting::findOne(['store_id' => $this->store->id]);
        $ignore = ['yy'];
        $type = [
            's' => '商城订单',
            'ms' => '秒杀订单',
            'pt' => '拼团订单',
            'yy' => '预约订单',
            'ds' => '多商户订单',
            'b' => '砍价订单',
        ];
        return $this->render('order', [
            'row_count' => $data['row_count'],
            'pagination' => $data['pagination'],
            'list' => $data['list'],
            'user' => $user,
            'setting' => $setting,
            'exportList' => \Yii::$app->serializer->encode($exportList),
            'ignore' => $ignore,
            'type' => $type
        ]);
    }

    public function actionCustom()
    {
        $form = new ShareCustomForm();
        $form->store_id = $this->store->id;
        if (\Yii::$app->request->isPost) {
            $form->attributes = \Yii::$app->request->post();
            return $form->saveData();
        } else {
            if (\Yii::$app->request->isAjax) {
                return $form->getData();
            } else {
                return $this->render('custom');
            }
        }
    }

    public function actionGetQrcode()
    {
        $form = new ShareGetQrcodeForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        return $form->search();
    }

    public function actionGetTeam()
    {
        $form = new ShareListForm();
        $form->store_id = $this->store->id;
        $get = \Yii::$app->request->get();
        return $form->getTeam1($get['user_id'], $get['level']);
    }
}
