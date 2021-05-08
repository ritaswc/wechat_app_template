<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;
use Codeception\PHPUnit\ResultPrinter\HTML;

/**
 * This is the model class for table "{{%cash}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $store_id
 * @property string $price
 * @property integer $status
 * @property integer $is_delete
 * @property integer $addtime
 * @property integer $pay_time
 * @property integer $type
 * @property string $mobile
 * @property string $name
 * @property string $bank_name
 * @property integer $pay_type
 * @property string $order_no
 * @property string $service_charge
 */
class Cash extends \yii\db\ActiveRecord
{
    public static $status = [
        '待审核',
        '待打款',
        '已打款',
        '无效'
    ];
    public static $type = [
        '微信线下支付',
        '支付宝支付',
        '银行卡支付',
        '余额支付',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cash}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'store_id', 'pay_time'], 'required'],
            [['user_id', 'store_id', 'status', 'is_delete', 'addtime', 'pay_time', 'type', 'pay_type'], 'integer'],
            [['price', 'service_charge'], 'number'],
            [['mobile', 'name', 'order_no'], 'string', 'max' => 255],
            [['bank_name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'store_id' => 'Store ID',
            'price' => '提现金额',
            'status' => '申请状态 0--申请中 1--确认申请 2--已打款 3--驳回',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
            'pay_time' => '付款',
            'type' => '支付方式 0--微信支付  1--支付宝',
            'mobile' => '支付宝账号',
            'name' => '支付宝姓名',
            'bank' => '银行卡选中状态',
            'bank_name' => '开户行',
            'pay_type' => '打款方式 0--之前未统计的 1--微信自动打款 2--手动打款',
            'order_no' => '微信自动打款订单号',
            'service_charge' => '提现手续费',
        ];
    }

    public function beforeSave($insert)
    {
        $this->name = \yii\helpers\Html::encode($this->name);
        $this->mobile = \yii\helpers\Html::encode($this->mobile);
        $this->bank_name = \yii\helpers\Html::encode($this->bank_name);
        return parent::beforeSave($insert);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getShare()
    {
        return $this->hasOne(Share::className(), ['user_id' => 'user_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        $title = '分销提现';
        CommonActionLog::storeActionLog($title, $insert, $this->is_delete, $data, $this->id);
    }

    public static function getServiceMoney($cash)
    {
        if($cash['service_charge'] == 0){
            $price = $cash['price'];
        }else{
            $cashPrice = floatval($cash['price']);
            $price = $cashPrice * (100 - $cash['service_charge']) / 100;
        }
        return round($price, 2);
    }
}
