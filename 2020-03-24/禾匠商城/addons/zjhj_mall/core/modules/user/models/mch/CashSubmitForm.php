<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/5/5
 * Time: 12:13
 */


namespace app\modules\user\models\mch;

use app\models\Mch;
use app\models\MchAccountLog;
use app\models\MchCash;
use app\modules\user\models\UserModel;

class CashSubmitForm extends UserModel
{
    public $mch_id;
    public $money;
    public $type;
    public $data;

    public function rules()
    {
        return [
            [['money'], 'required'],
            [['money'], 'number', 'min' => 1,],
            [['type'], 'integer'],
            [['type'], 'default', 'value' => 0],
            [['data'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'money' => '提现金额',
            'type' => '提现方式'
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $this->money = floatval(sprintf('%.2f', $this->money));
        $mch = Mch::findOne($this->mch_id);
        if (!$mch) {
            return [
                'code' => 1,
                'msg' => '商户不存在。',
            ];
        }
        if ($this->money > $mch->account_money) {
            return [
                'code' => 1,
                'msg' => '账户余额不足。',
            ];
        }
        $mch->account_money = $mch->account_money - $this->money;
        $cash = new MchCash();
        $cash->mch_id = $this->mch_id;
        $cash->money = $this->money;
        $cash->store_id = $mch->store_id;
        $cash->addtime = time();
        $cash->status = 0;
        $cash->order_no = 'MC' . date('YmdHis') . mt_rand(1000, 9999);
        $cash->type = $this->type;
        $cash->type_data = $this->data ? \Yii::$app->serializer->encode($this->data) : "";
        if ($cash->save()) {
            $mch->save(false);
            $log = new MchAccountLog();
            $log->store_id = $mch->store_id;
            $log->mch_id = $mch->id;
            $log->type = 2;
            $log->desc = '提现';
            $log->price = $this->money;
            $log->addtime = time();
            $log->save();
            return [
                'code' => 0,
                'msg' => '提现申请已提交，请等待管理审核。'
            ];
        }
        return $this->getErrorResponse($cash);
    }
}
