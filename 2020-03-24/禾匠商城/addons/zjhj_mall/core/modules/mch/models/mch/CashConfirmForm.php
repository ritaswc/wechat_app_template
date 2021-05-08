<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/4/29
 * Time: 20:20
 */


namespace app\modules\mch\models\mch;

use app\models\Mch;
use app\models\MchAccountLog;
use app\models\MchCash;
use app\models\User;
use app\modules\mch\models\MchModel;

class CashConfirmForm extends MchModel
{
    public $id;
    public $store_id;
    public $status;

    public function rules()
    {
        return [
            [['id', 'status'], 'required'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $cash = MchCash::findOne([
            'id' => $this->id,
            'store_id' => $this->store_id,
            'status' => 0,
        ]);
        if (!$cash) {
            return [
                'code' => 1,
                'msg' => '提现记录不存在。',
            ];
        }
        $mch = Mch::findOne($cash->mch_id);

        if ($this->status == 2) {//拒绝，资金返回
            $cash->status = 2;
            $mch->account_money = floatval($mch->account_money) + floatval($cash->money);
            $mch->save(false);
            $cash->save(false);
            $log = new MchAccountLog();
            $log->mch_id = $mch->id;
            $log->store_id = $mch->store_id;
            $log->type = 1;
            $log->price = $cash->money;
            $log->desc = '提现被拒绝，资金返回账户';
            $log->addtime = time();
            $log->save();
            return [
                'code' => 0,
                'msg' => '操作成功。',
            ];
        }
        if ($this->status == 1) {//同意，微信支付到用户零钱
            $user = User::findOne($mch->user_id);
            $wechat = $this->getWechat();
            $res = $wechat->pay->transfers([
                'partner_trade_no' => $cash->order_no,
                'openid' => $user->wechat_open_id,
                'amount' => $cash->money * 100,
                'desc' => '入驻商提现',
            ]);
            if (!$res) {
                return [
                    'code' => 1,
                    'msg' => '转账失败，请检查微信配置是否正确。'
                ];
            }
            if ($res['return_code'] != 'SUCCESS') {
                return [
                    'code' => 1,
                    'msg' => '转账失败：' . $res['return_msg'],
                    'res' => $res,
                ];
            }
            if ($res['result_code'] != 'SUCCESS') {
                return [
                    'code' => 1,
                    'msg' => '转账失败：' . $res['err_code_des'],
                    'res' => $res,
                ];
            }
            if ($res['result_code'] == 'SUCCESS') {
                $cash->status = 1;
                $cash->virtual_type = 0;
                $cash->save(false);
                return [
                    'code' => 0,
                    'msg' => '转账成功。',
                    'res' => $res,
                ];
            }
        }
        if ($this->status == 3) {
            $user = User::findOne($mch->user_id);
            if ($cash->type == 4) {
                $user->money = floatval($user->money) + floatval($cash->money);
                $user->save(false);
            }
            $cash->status = 1;
            $cash->virtual_type = $cash->type;
            if ($cash->type == 0) {
                $cash->virtual_type = $user->platform == 0 ? 1 : 2;
            }
            $cash->save(false);
            return [
                'code' => 0,
                'msg' => '操作成功。',
            ];
        }
    }
}
