<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/9
 * Time: 16:04
 */

namespace app\modules\api\models;

use app\models\Cash;
use app\models\common\CommonFormId;
use app\models\Model;
use app\models\Order;
use app\models\Setting;
use app\models\tplmsg\AdminTplMsgSender;
use app\models\User;

/**
 * @property \app\models\Share $share
 */
class ShareForm extends ApiModel
{
    public $share;
    public $store_id;
    public $user_id;

    public $name;
    public $mobile;
    public $agree;
    public $form_id;

    /**
     * @return array
     * 场景说明：NONE_CONDITION--无条件
     *           APPLY--需要申请
     */
    public function rules()
    {
        return [
            [['name', 'mobile', 'agree'], 'required', 'on' => 'APPLY'],
            [['agree'], 'integer'],
            [['name', 'mobile', 'form_id'], 'trim'],
            [['mobile'], 'match', 'pattern' => Model::MOBILE_PATTERN, 'message' => '手机号错误', 'on' => 'APPLY']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '真实姓名',
            'mobile' => '手机号'
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $t = \Yii::$app->db->beginTransaction();
            if (($this->agree || $this->agree == 0) && $this->agree != 1) {
                return [
                    'code' => 1,
                    'msg' => '请先阅读并确认分销申请协议'
                ];
            }
            $this->share->attributes = $this->attributes;
            if ($this->share->isNewRecord) {
                $this->share->is_delete = 0;
                $this->share->addtime = time();
                $this->share->store_id = $this->store_id;
            } else {
                if ($this->share->status == 1) {
                    return [
                        'code' => 1,
                        'msg '=> '用户已经是分销商'
                    ];
                }
            }
            $this->share->user_id = \Yii::$app->user->identity->id;
            $user = User::findOne(['id' => \Yii::$app->user->identity->id, 'store_id' => $this->store_id]);
            $share_setting = Setting::findOne(['store_id' => $this->store_id]);
            if ($share_setting->share_condition != 2) {
                $user->is_distributor = 2;
                $this->share->status = 0;
                $user->time = 0;
            } else {
                $user->is_distributor = 1;
                $this->share->status = 1;
                $user->time = time();
            }
            if (!$user->save()) {
                $t->rollBack();
                return [
                    'code' => 1,
                    'msg' => '网络异常'
                ];
            }
            if ($this->share->save()) {
                $res = CommonFormId::save([
                   [
                       'form_id' => $this->form_id
                   ]
                ]);
                $t->commit();
                AdminTplMsgSender::sendFxsApply($this->store_id, [
                    'time' => date('Y-m-d H:i:s'),
                    'content' => '用户：' . $user->nickname,
                ]);
                return [
                    'code' => 0,
                    'msg' => '成功'
                ];
            } else {
                $t->rollBack();
                return [
                    'code' => 1,
                    'msg' => '网络异常',
                    'data' => $this->errors,
                ];
            }
        } else {
            return $this->errorResponse;
        }
    }

    /**
     * @return array
     * 获取佣金相关信息
     */
    public function getPrice()
    {
        $user = User::find()->where(['id' => $this->user_id])->one();
        $list = Cash::find()->where([
            'store_id' => $this->store_id, 'user_id' => $this->user_id, 'is_delete' => 0
        ])->asArray()->all();
        $new_list = [];
        $new_list['total_price'] = $user->total_price;//分销佣金
        $new_list['price'] = $user->price;
        ;//可提现
        $new_list['cash_price'] = 0;//已提现
        $new_list['un_pay'] = 0;//未审核
        $new_list['total_cash'] = 0;//提现明细
        foreach ($list as $index => $value) {
            if ($value['status'] == 1) {
                $new_list['un_pay'] = round(($new_list['un_pay'] + $value['price']), 2);
                $new_list['total_cash'] = round(($new_list['total_cash'] + $value['price']), 2);
            } elseif ($value['status'] == 2 || $value['status'] == 5) {
                $new_list['cash_price'] = round(($new_list['cash_price'] + $value['price']), 2);
                $new_list['total_cash'] = round(($new_list['total_cash'] + $value['price']), 2);
            }
        }
        return $new_list;
    }

    /**
     * @return array|null|\yii\db\ActiveRecord
     *
     */
    public function getCash()
    {
        $list = User::find()->alias('u')
            ->where(['u.is_delete' => 0, 'u.store_id' => $this->store_id, 'u.id' => $this->user_id])
            ->leftJoin('{{%cash}} c', 'c.user_id=u.id and c.is_delete=0')
            ->select([
                'u.total_price', 'u.price',
                'sum(case when c.status = 2 then c.price else 0 end) cash_price',
                'sum(case when c.status = 1 then c.price else 0 end) un_pay'
            ])->groupBy('c.user_id')->asArray()->one();
        return $list;
    }

    //获取分销团队总人数
    public function getTeamCount()
    {
        $share_setting = Setting::findOne(['store_id' => $this->store_id]);
        if (!$share_setting || $share_setting->level == 0) {
            return [
                'team_count' => 0,
                'team' => []
            ];
        }
        $team = [];
        $first = User::find()->select(['id'])
            ->where(['store_id' => $this->store_id, 'parent_id' => $this->user_id, 'is_delete' => 0])->column();
        $count = count($first);
        $team['f_c'] = $first;
        if ($share_setting->level >= 2) {
            $second = User::find()->select(['id'])
                ->where(['store_id' => $this->store_id, 'parent_id' => $first, 'is_delete' => 0])->column();
            $count += count($second);
            $team['s_c'] = $second;
            if ($share_setting->level >= 3) {
                $third = User::find()->select(['id'])
                    ->where(['store_id' => $this->store_id, 'parent_id' => $second, 'is_delete' => 0])->column();
                $count += count($third);
                $team['t_c'] = $third;
            }
        }
        return [
            'team_count' => $count,
            'team' => $team
        ];
    }

    public function getOrder()
    {
        $arr = $this->getTeamCount();
        $team_arr = $arr['team'];

        $order_money = 0;
        $first_price = Order::find()->alias('o')->where([
            'o.is_delete' => 0, 'o.is_cancel' => 0, 'o.store_id' => $this->store_id
        ])->andWhere([
            'o.parent_id' => $this->user_id,
        ])->select(['sum(first_price)'])->scalar();
        if ($first_price) {
            $order_money += doubleval($first_price);
        }
        if (!empty($team_arr['s_c'])) {
            $second_price = Order::find()->alias('o')->where([
                'o.is_delete' => 0, 'o.is_cancel' => 0, 'o.store_id' => $this->store_id
            ])->andWhere([
                'or',
                ['and', ['in', 'o.user_id', $team_arr['s_c']], ['o.parent_id' => $team_arr['f_c'], 'o.parent_id_1' => 0]],
                ['o.parent_id_1' => $this->user_id],
            ])->select(['sum(second_price)'])->scalar();
            if ($second_price) {
                $order_money += doubleval($second_price);
            }
        }
        if (!empty($team_arr['t_c'])) {
            $third_price = Order::find()->alias('o')->where([
                'o.is_delete' => 0, 'o.is_cancel' => 0, 'o.store_id' => $this->store_id
            ])->andWhere([
                'or',
                ['and', ['in', 'o.user_id', $team_arr['t_c']], ['o.parent_id' => $team_arr['s_c'], 'o.parent_id_1' => 0]],
                ['o.parent_id_2' => $this->user_id],
            ])->select(['sum(third_price)'])->scalar();
            if ($third_price) {
                $order_money += doubleval($third_price);
            }
        }
        $arr['order_money'] = doubleval(sprintf('%.2f', $order_money));

        return $arr;
    }
}
