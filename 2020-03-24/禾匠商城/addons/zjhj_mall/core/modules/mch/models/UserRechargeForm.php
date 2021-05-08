<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/8
 * Time: 16:30
 */

namespace app\modules\mch\models;

use app\models\IntegralLog;
use app\models\User;
use app\models\UserAccountLog;

class UserRechargeForm extends MchModel
{
    public $store_id;
    public $admin;

    public $user_id;
    public $type;
    public $rechargeType;
    public $money;
    public $pic_url;
    public $explain;

    public function rules()
    {
        return [
            [['user_id', 'type', 'rechargeType'], 'integer'],
            [['money'], 'number', 'min' => 0],
            [['pic_url', 'explain'], 'trim'],
            [['pic_url', 'explain'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'money' => '输入金额',
            'pic_url' => '图片',
            'explain' => '说明',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $user = User::findOne($this->user_id);
        if (!$user) {
            return [
                'code' => 1,
                'msg' => '用户不存在'
            ];
        }
        $this->money = floatval($this->money);
        if ($this->money < 0) {
            return [
                'code' => 1,
                'msg' => '输入数值不能小于0'
            ];
        }

        $integralLog = new IntegralLog();
        $integralLog->store_id = $this->store_id;
        $integralLog->user_id = $this->user_id;
        $integralLog->pic_url = $this->pic_url;
        $integralLog->explain = $this->explain;

        $userAccountLog = new UserAccountLog();
        $userAccountLog->user_id = $user->id;

        switch ($this->rechargeType) {
            case 1:
                $user->money += $this->money;
                $integralLog->content = "管理员： " . $this->admin->username . " 后台操作账号：" . $user->nickname . " 余额充值：" . $this->money . " 元";
                $userAccountLog->desc = "管理员： " . $this->admin->username . " 后台操作账号：" . $user->nickname . " 余额充值：" . $this->money . " 元";
                $userAccountLog->type = 1;
                break;
            case 2:
                if ($user->money < $this->money) {
                    return [
                        'code' => 1,
                        'msg' => '扣除数值大于当前用户余额'
                    ];
                }
                $user->money -= $this->money;
                $integralLog->content = "管理员： " . $this->admin->username . " 后台操作账号：" . $user->nickname . " 余额扣除：" . $this->money . " 元";
                $userAccountLog->desc = "管理员： " . $this->admin->username . " 后台操作账号：" . $user->nickname . " 余额扣除：" . $this->money . " 元";
                $userAccountLog->type = 2;
                break;
            default:
                return [
                    'code' => 1,
                    'msg' => '网络异常，请刷新重试'
                ];
        }
        $t = \Yii::$app->db->beginTransaction();
        if ($user->save()) {
            $integralLog->integral = $this->money;
            $integralLog->username = trim($user->nickname) ? $user->nickname : '未知';
            $integralLog->operator = $this->admin->username;
            $integralLog->operator_id = $this->admin->id;
            $integralLog->addtime = time();
            $integralLog->type = 1;
            if (!$integralLog->save()) {
                $t->rollBack();
                return $this->getErrorResponse($integralLog);
            }
            $userAccountLog->price = $this->money;
            $userAccountLog->order_type = 7;
            $userAccountLog->order_id = $integralLog->id;
            $userAccountLog->addtime = time();
            if (!$userAccountLog->save()) {
                $t->rollBack();
                return $this->getErrorResponse($userAccountLog);
            } else {
                $t->commit();
                return [
                    'code' => 0,
                    'msg' => '操作成功'
                ];
            }
        } else {
            $t->rollBack();
            return $this->getErrorResponse($user);
        }
    }
}
