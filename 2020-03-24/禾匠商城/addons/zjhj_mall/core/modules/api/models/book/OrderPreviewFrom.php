<?php
/**
 * Created by PhpStorm.
 * User: peize
 * Date: 2017/12/14
 * Time: 11:32
 */

namespace app\modules\api\models\book;

use Alipay\AlipayRequestFactory;
use app\hejiang\ApiCode;
use app\models\common\api\CommonOrder;
use app\models\common\CommonGoods;
use app\models\FormId;
use app\models\GoodsShare;
use app\models\Option;
use app\models\OrderShare;
use app\models\OrderWarn;
use app\models\Setting;
use app\models\User;
use app\models\UserAccountLog;
use app\models\YyForm;
use app\models\YyGoods;
use app\models\YyOrder;
use app\models\YyOrderForm;
use app\models\YySetting;
use app\modules\api\models\ApiModel;
use app\modules\api\models\ShareMoneyForm;

class OrderPreviewFrom extends ApiModel
{
    public $store_id;
    public $user_id;

    public $goods_id;

    public $form_list;
    public $form_id;

    public $pay_type;

    private $wechat;
    private $order;
    private $user;

    public $attr;
    public $parent_user_id;

    public function search()
    {
        $goods = YyGoods::find()
            ->andWhere(['id' => $this->goods_id, 'is_delete' => 0, 'status' => 1, 'store_id' => $this->store_id])
            ->one();


        $attr = json_decode($this->attr, true);
        $attr_id_list = [];
        foreach ($attr as &$v) {
            $attr_id_list[] = $v['attr_id'];
        };
        unset($v);

//            $goods['price'] =  $goods->getAttrInfo($attr_id_list)['price'];
        $goods_attr_info = CommonGoods::currentGoodsAttr([
            'attr' => $goods['attr'],
            'price' => $goods['price'],
            'is_level' => $goods['is_level'],
        ], $attr_id_list);

        $goods['price'] = $goods_attr_info['price'];
        $goods['is_level'] = $goods_attr_info['is_level'];


        $attr_list = $goods->checkAttr($this->attr);
        if ($attr_list['num'] <= 0) {
            return [
                'code' => 1,
                'msg' => '商品库存不足'
            ];
        }
        try {
            $this->checkGoods($goods);
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage()
            ];
        }

        $formList = YyForm::find()
            ->andWhere(['goods_id' => $this->goods_id, 'is_delete' => 0, 'store_id' => $this->store_id])
            ->orderBy('sort DESC')
            ->asArray()
            ->all();
        foreach ($formList as $k => $v) {
            if ($v['type'] == 'radio' || $v['type'] == 'checkbox') {
//                $formList[$k]['default'] = explode(',' , $v['default']);
                $defaultArr = explode(',', trim($v['default'], ','));
                $defaultArr2 = [];
                foreach ($defaultArr as $key => $value) {
                    $defaultArr2[$key]['name'] = $value;
                    if ($key == 0) {
                        $defaultArr2[$key]['selected'] = true;
                    } else {
                        $defaultArr2[$key]['selected'] = false;
                    }
                }
                $formList[$k]['default'] = $defaultArr2;
            }
            if ($v['type'] == 'date') {
                $formList[$k]['default'] = $v['default'] ?: 0;
            }
        }
        $option = Option::get('yy_payment', $this->store_id, 'admin');
        $option = json_decode($option, true);


        return [
            'code' => 0,
            'msg' => '成功',
            'data' => [
                'goods' => $goods,
                'form_list' => $formList,
                'option' => $option,
                'level_price' => sprintf('%.2f', $goods_attr_info['level_price']),
            ],
        ];
    }

    public function save()
    {
        $this->wechat = $this->getWechat();

        $res = CommonOrder::checkOrder();
        if ($res['code'] === 1) {
            return $res;
        }

        $goods = YyGoods::find()
            ->andWhere(['id' => $this->goods_id, 'is_delete' => 0, 'status' => 1, 'store_id' => $this->store_id])->one();
        if (!$goods) {
            return [
                'code' => 1,
                'msg' => '商品不存在',
            ];
        }
        try {
            $this->checkGoods($goods);
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage()
            ];
        }

        $attr = json_decode($this->attr, true);
        $attr_id_list = [];
        foreach ($attr as &$v) {
            $attr_id_list[] = $v['attr_id'];
        };
        unset($v);

//        $attr_list = $goods->checkAttr($this->attr);
        $attr_list = CommonGoods::currentGoodsAttr([
            'attr' => $goods['attr'],
            'price' => $goods['price'],
            'is_level' => $goods['is_level'],
        ], $attr_id_list);

        $attr_list['attr'] = $this->attr;
        $price = $attr_list['price'];
        if ($attr_list['num'] <= 0) {
            return [
                'code' => 1,
                'msg' => '商品库存不足'
            ];
        }
        $p = \Yii::$app->db->beginTransaction();

        $this->user = User::findOne(['id' => $this->user_id, 'type' => 1, 'is_delete' => 0]);

        $order = new YyOrder();
        $order->store_id = $this->store_id;
        $order->goods_id = $goods->id;
        $order->user_id = $this->user_id;
        $order->order_no = $this->getOrderNo();
        $order->total_price = $price;
        $order->pay_price = $price;
        $order->is_pay = 0;
        $order->is_use = 0;
        $order->is_comment = 0;
        $order->addtime = time();
        $order->is_delete = 0;
        $order->form_id = $this->form_id;
        $order->attr = $attr_list['attr'];
        if ($order->save()) {
            $commonOrder = CommonOrder::saveParentId($this->parent_user_id);

            $attr = json_decode($order->attr, true);
            $attr_id_list = [];
            foreach ($attr as $v) {
                $attr_id_list[] = $v['attr_id'];
            };
            $goods->numSub($attr_id_list, 1);

            if (!$goods->use_attr) {
                $goods->stock--;
            }

            $goods->sales++;
            // $goods->stock--;
            $goods->save();
            foreach ($this->form_list as $key => $value) {
                if ($value['required'] == 1 && $value['default'] == '') {
                    return [
                        'code' => 1,
                        'msg' => $value['name'] . '不能为空',
                    ];
                }
                if ($value['type'] == 'radio' || $value['type'] == 'checkbox') {
                    $default = [];
                    foreach ($value['default'] as $k => $v) {
                        if ($v['selected'] == true) {
                            $default[$k] = $v['name'];
                        }
                    }
                    $value['default'] = implode($default, ',');
                    if ($value['required'] == 1 && empty($value['default'])) {
                        return [
                            'code' => 1,
                            'msg' => $value['name'] . '不能为空',
                        ];
                    }
                }

                $formList = new YyOrderForm();
                $formList->store_id = $this->store_id;
                $formList->goods_id = $goods->id;
                $formList->user_id = $this->user_id;
                $formList->order_id = $order->id;
                $formList->key = $value['name'];
                $formList->value = $value['default'];
                $formList->is_delete = 0;
                $formList->addtime = time();
                $formList->type = $value['type'];

                if (!$formList->save()) {
                    $p->rollBack();
                    return [
                        'code' => 1,
                        'msg' => '订单提交失败，请稍后重试',
                    ];
                }
            }

            if ($order->pay_price <= 0) {
                $order->is_pay = 1;
                $order->pay_type = 1;
                $order->pay_time = time();
                if ($order->save()) {
                    //支付完成之后，相关操作
                    $form = new OrderWarn();
                    $form->order_id = $order->id;
                    $form->order_type = 3;
                    $form->notify();

                    $p->commit();
                    return [
                        'code' => 0,
                        'msg' => '订单提交成功',
                        'type' => 1,
                    ];
                } else {
                    $p->rollBack();
                    return [
                        'code' => 1,
                        'msg' => '订单提交失败，请稍后重试',
                    ];
                }
            }

            $this->order = $order;
            $goods_names = mb_substr($goods->name, 0, 32, 'utf-8');
            $pay_data = [];
            $res = null;

            //余额支付数据处理
            if ($this->pay_type == 'BALANCE_PAY') {
                $user = User::findOne(['id' => $this->order->user_id]);
                if ($user->money < $this->order->pay_price) {
                    return [
                        'code' => 1,
                        'msg' => '支付失败，余额不足'
                    ];
                }
                $user->money -= floatval($this->order->pay_price);
                $user->save();

                $log = new UserAccountLog();
                $log->user_id = $user->id;
                $log->type = 2;
                $log->price = floatval($this->order->pay_price);
                $log->addtime = time();
                $log->order_type = 10;
                $log->desc = '预约购买,订单号为：' . $order->order_no;
                $log->order_id = $order->id;
                $log->save();

                $order->is_pay = 1;
                $order->pay_time = time();
                $order->pay_type = 2;
                $this->setReturnData($this->order);

                if ($order->save()) {
                    //支付完成之后，相关操作
                    $form = new OrderWarn();
                    $form->order_id = $order->id;
                    $form->order_type = 3;
                    $form->notify();
                    $p->commit();
                    return [
                        'code' => 0,
                        'msg' => 'success',
                        'type' => 1,
                    ];
                } else {
                    $p->rollBack();
                    return [
                        'code' => 1,
                        'msg' => '支付失败',
                        'data' => $this->getErrorResponse($order),
                    ];
                }
            };

            if ($this->pay_type == 'WECHAT_PAY') {

                // 支付宝支付
                if (\Yii::$app->fromAlipayApp()) {
                    $request = AlipayRequestFactory::create('alipay.trade.create', [
                        'notify_url' => pay_notify_url('/alipay-notify.php'),
                        'biz_content' => [
                            'body' => $goods_names, // 对一笔交易的具体描述信息。如果是多种商品，请将商品描述字符串累加
                            'subject' => $goods_names, // 商品的标题 / 交易标题 / 订单标题 / 订单关键字等
                            'out_trade_no' => $this->order->order_no, // 商户网站唯一订单号
                            'total_amount' => $this->order->pay_price, // 订单总金额，单位为元，精确到小数点后两位，取值范围 [0.01,100000000]
                            'buyer_id' => $this->user->wechat_open_id, // 购买人的支付宝用户 ID

                        ],
                    ]);

                    $aop = $this->getAlipay();

                    $res = $aop->execute($request)->getData();
                    $order->form_id = $this->form_id;
                    $order->save();
                    $this->setReturnData($this->order);

                    $p->commit();
                    return [
                        'code' => 0,
                        'msg' => '订单提交成功',
                        'data' => $res,
                        'res' => $res,
                        'body' => $goods_names,
                    ];
                }

                // 微信支付
                if (\Yii::$app->fromWechatApp()) {
                    $res = $this->unifiedOrder($goods_names);
                    if (isset($res['code']) && $res['code'] == 1) {
                        return $res;
                    }

                    //记录prepay_id发送模板消息用到
                    //                YyFormId::addFormId([
                    //                    'store_id' => $this->store_id,
                    //                    'user_id' => $this->user->id,
                    //                    'wechat_open_id' => $this->user->wechat_open_id,
                    //                    'form_id' => $res['prepay_id'],
                    //                    'type' => 'prepay_id',
                    //                    'order_no' => $this->order->order_no,
                    //                ]);
                    $order->form_id = $res['prepay_id'];
                    $order->save();
                    $pay_data = [
                        'appId' => $this->wechat->appId,
                        'timeStamp' => '' . time(),
                        'nonceStr' => md5(uniqid()),
                        'package' => 'prepay_id=' . $res['prepay_id'],
                        'signType' => 'MD5',
                    ];
                    $pay_data['paySign'] = $this->wechat->pay->makeSign($pay_data);
                    $this->setReturnData($this->order);
                    //                return [
                    //                    'code' => 0,
                    //                    'msg' => 'success',
                    //                    'data' => (object)$pay_data,
                    //                    'res' => $res,
                    //                    'body' => $goods_names,
                    //                ];
                }
            }

            $p->commit();
            return [
                'code' => 0,
                'msg' => '订单提交成功',
                'data' => (object)$pay_data,
                'res' => $res,
                'body' => $goods_names,
                'type' => 2,
            ];
        } else {
            $p->rollBack();
            return $this->getErrorResponse($order);
        }
    }

    /**
     * @return null|string
     * 生成订单号
     */
    public function getOrderNo()
    {
        $store_id = empty($this->store_id) ? 0 : $this->store_id;
        $order_no = null;
        while (true) {
            $order_no = 'Y' . date('YmdHis') . mt_rand(10000, 99999);
            $exist_order_no = YyOrder::find()->where(['order_no' => $order_no])->exists();
            if (!$exist_order_no) {
                break;
            }
        }
        return $order_no;
    }

    /**
     * @param $goods_names
     * @return array
     * 统一下单
     */
    private function unifiedOrder($goods_names)
    {
        $res = $this->wechat->pay->unifiedOrder([
            'body' => $goods_names,
            'out_trade_no' => $this->order->order_no,
            'total_fee' => $this->order->pay_price * 100,
            'notify_url' => pay_notify_url('/pay-notify.php'),
            'trade_type' => 'JSAPI',
            'openid' => $this->user->wechat_open_id,
        ]);

        if (!$res) {
            return [
                'code' => 1,
                'msg' => '支付失败',
            ];
        }
        if ($res['return_code'] != 'SUCCESS') {
            return [
                'code' => 1,
                'msg' => '支付失败，' . (isset($res['return_msg']) ? $res['return_msg'] : ''),
                'res' => $res,
            ];
        }
        if ($res['result_code'] != 'SUCCESS') {
            if ($res['err_code'] == 'INVALID_REQUEST') { //商户订单号重复
                $this->order->order_no = $this->getOrderNo();
                $this->order->save();
                return $this->unifiedOrder($goods_names);
            } else {
                return [
                    'code' => 1,
                    'msg' => '支付失败，' . (isset($res['err_code_des']) ? $res['err_code_des'] : ''),
                    'res' => $res,
                ];
            }
        }
        return $res;
    }

    public function payData($id)
    {
        $this->wechat = $this->getWechat();
        $this->user = User::findOne(['id' => $this->user_id, 'type' => 1, 'is_delete' => 0]);
        $order = YyOrder::find()
            ->andWhere([
                'is_delete' => 0,
                'store_id' => $this->store_id,
                'user_id' => $this->user_id,
                'is_cancel' => 0,
                'id' => $id,
                'is_pay' => 0,
            ])->one();
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在，或已支付',
            ];
        }

        $this->order = $order;
        try {
            $goods = YyGoods::find()
                ->andWhere(['id' => $order->goods_id, 'is_delete' => 0, 'status' => 1, 'store_id' => $order->store_id])->one();
            $this->checkGoods($goods);
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage()
            ];
        }

        //余额支付数据处理  XXX
        if ($this->pay_type == 'BALANCE_PAY') {

            $p = \Yii::$app->db->beginTransaction();
            $user = User::findOne(['id' => $order->user_id]);
            if ($user->money < $order->pay_price) {
                return [
                    'code' => 1,
                    'msg' => '支付失败，余额不足'
                ];
            }
            $user->money -= floatval($order->pay_price);
            $user->save();

            $log = new UserAccountLog();
            $log->user_id = $user->id;
            $log->type = 2;
            $log->price = floatval($order->pay_price);
            $log->addtime = time();
            $log->order_type = 10;
            $log->desc = '预约购买,订单号为：' . $order->order_no;
            $log->order_id = $order->id;
            $log->save();

            $order->is_pay = 1;
            $order->pay_time = time();
            $order->pay_type = 2;

            $this->setReturnData($this->order);
            if ($order->save()) {
                $p->commit();
                return [
                    'code' => 0,
                    'msg' => 'success',
                    'type' => 1,
                ];
            } else {
                $p->rollBack();
                return [
                    'code' => 1,
                    'msg' => '支付失败',
                    'data' => $this->getErrorResponse($order),
                ];
            }
        };

        $goods = YyGoods::findOne(['id' => $order->goods_id]);

        $goods_names = mb_substr($goods->name, 0, 32, 'utf-8');
        $this->pay_type = 'WECHAT_PAY';
        if ($this->pay_type == 'WECHAT_PAY') {
            if (\Yii::$app->fromAlipayApp()) {
                $request = AlipayRequestFactory::create('alipay.trade.create', [
                    'notify_url' => pay_notify_url('/alipay-notify.php'),
                    'biz_content' => [
                        'body' => $goods_names, // 对一笔交易的具体描述信息。如果是多种商品，请将商品描述字符串累加
                        'subject' => $goods_names, // 商品的标题 / 交易标题 / 订单标题 / 订单关键字等
                        'out_trade_no' => $this->order->order_no, // 商户网站唯一订单号
                        'total_amount' => $this->order->pay_price, // 订单总金额，单位为元，精确到小数点后两位，取值范围 [0.01,100000000]
                        'buyer_id' => $this->user->wechat_open_id, // 购买人的支付宝用户 ID

                    ],
                ]);

                $aop = $this->getAlipay();
                $res = $aop->execute($request)->getData();
                $order->save();
                $this->setReturnData($order);
                return [
                    'code' => 0,
                    'msg' => '订单提交成功',
                    'data' => $res,
                    'res' => $res,
                    'body' => $goods_names,
                ];
            }

            $res = $this->unifiedOrder($goods_names);
            if (isset($res['code']) && $res['code'] == 1) {
                return $res;
            }

            //记录prepay_id发送模板消息用到
            FormId::addFormId([
                'store_id' => $this->store_id,
                'user_id' => $this->user->id,
                'wechat_open_id' => $this->user->wechat_open_id,
                'form_id' => $res['prepay_id'],
                'type' => 'prepay_id',
                'order_no' => $this->order->order_no,
            ]);
            $order->form_id = $res['prepay_id'];
            $order->save();

            $pay_data = [
                'appId' => $this->wechat->appId,
                'timeStamp' => '' . time(),
                'nonceStr' => md5(uniqid()),
                'package' => 'prepay_id=' . $res['prepay_id'],
                'signType' => 'MD5',
            ];
            $pay_data['paySign'] = $this->wechat->pay->makeSign($pay_data);
            $this->setReturnData($order);
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => (object)$pay_data,
                'res' => $res,
                'body' => $goods_names,
            ];
        }
    }

    /**
     * 设置佣金
     */
    private function setReturnData($order)
    {
        $form = new ShareMoneyForm();
        $form->order = $order;
        $form->order_type = 3;
        return $form->setData();
    }

    /**
     * 设置佣金
     * @param YyOrder $pt_order
     * @param int $type
     * 2018-05-23 风哀伤 废弃
     */
    private function setReturnData_1($pt_order, $type = 1)
    {
        //总设置是否开启分销
        $setting = Setting::findOne(['store_id' => $pt_order->store_id]);
        if (!$setting || $setting->level == 0) {
            return false;
        }
        //预约设置是否开启分销
        $pt_setting = YySetting::findOne(['store_id' => $this->store_id]);
        if (!$pt_setting || $pt_setting->is_share == 0) {
            return false;
        }
        $user = User::findOne($pt_order->user_id); //订单本人
        if (!$user) {
            return false;
        }
        $order = OrderShare::findOne(['order_id' => $pt_order->id, 'type' => $type, 'is_delete' => 0, 'store_id' => $this->store_id]);
        if (!$order) {
            $order = new OrderShare();
            $order->order_id = $pt_order->id;
            $order->store_id = $pt_order->store_id;
            $order->is_delete = 0;
            $order->version = hj_core_version();
            $order->user_id = $pt_order->user_id;
        }
        $order->type = 1;
        $order->parent_id_1 = $user->parent_id;
        $parent = User::findOne($user->parent_id); //上级
        if ($parent->parent_id) {
            $order->parent_id_2 = $parent->parent_id;
            $parent_1 = User::findOne($parent->parent_id); //上上级
            if ($parent_1->parent_id) {
                $order->parent_id_3 = $parent_1->parent_id;
            } else {
                $order->parent_id_3 = -1;
            }
        } else {
            $order->parent_id_2 = -1;
            $order->parent_id_3 = -1;
        }

        $order_detail_list = YyOrder::find()->alias('od')->leftJoin(['g' => GoodsShare::tableName()], 'od.goods_id=g.goods_id and g.type = ' . $type)
            ->where(['od.is_delete' => 0, 'od.id' => $pt_order->id])
            ->asArray()
            ->select(['od.*', 'g.*'])
            ->all();
        $share_commission_money_first = 0; //一级分销总佣金
        $share_commission_money_second = 0; //二级分销总佣金
        $share_commission_money_third = 0; //三级分销总佣金
        $rebate = 0; //自购返利
        foreach ($order_detail_list as $item) {
            $item_price = doubleval($item['total_price']);
            if ($item_price == 0) {
                continue;
            }
            if ($item['individual_share'] == 1) {
                $rate_first = doubleval($item['share_commission_first']);
                $rate_second = doubleval($item['share_commission_second']);
                $rate_third = doubleval($item['share_commission_third']);
                $rate_rebate = doubleval($item['rebate']);
                if ($item['share_type'] == 1) {
                    $share_commission_money_first += $rate_first;
                    $share_commission_money_second += $rate_second;
                    $share_commission_money_third += $rate_third;
                    $rebate += $rate_rebate;
                } else {
                    $share_commission_money_first += $item_price * $rate_first / 100;
                    $share_commission_money_second += $item_price * $rate_second / 100;
                    $share_commission_money_third += $item_price * $rate_third / 100;
                    $rebate += $item_price * $rate_rebate / 100;
                }
            } else {
                $rate_first = doubleval($setting->first);
                $rate_second = doubleval($setting->second);
                $rate_third = doubleval($setting->third);
                $rate_rebate = doubleval($setting->rebate);
                if ($setting->price_type == 1) {
                    $share_commission_money_first += $rate_first;
                    $share_commission_money_second += $rate_second;
                    $share_commission_money_third += $rate_third;
                    $rebate += $rate_rebate;
                } else {
                    $share_commission_money_first += $item_price * $rate_first / 100;
                    $share_commission_money_second += $item_price * $rate_second / 100;
                    $share_commission_money_third += $item_price * $rate_third / 100;
                    $rebate += $item_price * $rate_rebate / 100;
                }
            }
        }
        //下单用户不是分销商，则不参与自购返利
        if ($user->is_distributor == 0) {
            $rebate = 0;
        }
        if ($setting->is_rebate == 0) {
            $rebate = 0;
        }

        $order->first_price = $share_commission_money_first < 0.01 ? 0 : $share_commission_money_first;
        $order->second_price = $share_commission_money_second < 0.01 ? 0 : $share_commission_money_second;
        $order->third_price = $share_commission_money_third < 0.01 ? 0 : $share_commission_money_third;
        $order->rebate = $rebate < 0.01 ? 0 : $rebate;
        $order->save();
    }

    /**
     * @param $goods
     * @throws \Exception
     */
    private function checkGoods($goods)
    {
        $userOrderCount = YyOrder::find()->andWhere([
            'goods_id' => $goods['id'],
            'apply_delete' => 0,
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
        ])->count();
        if ($userOrderCount >= $goods['buy_limit'] && $goods['buy_limit'] != 0) {
            throw new \Exception('超过该商品购买次数');
        }
    }
}
