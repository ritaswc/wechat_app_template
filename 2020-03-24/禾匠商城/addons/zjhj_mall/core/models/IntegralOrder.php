<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%integral_order}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property string $order_no
 * @property string $total_price
 * @property string $pay_price
 * @property string $express_price
 * @property string $name
 * @property string $mobile
 * @property string $address
 * @property string $remark
 * @property integer $is_pay
 * @property integer $pay_type
 * @property integer $pay_time
 * @property integer $is_send
 * @property integer $send_time
 * @property string $express
 * @property string $express_no
 * @property integer $is_confirm
 * @property integer $confirm_time
 * @property integer $is_comment
 * @property integer $apply_delete
 * @property integer $addtime
 * @property integer $is_delete
 * @property string $address_data
 * @property integer $is_offline
 * @property integer $clerk_id
 * @property integer $is_cancel
 * @property string $offline_qrcode
 * @property integer $shop_id
 * @property integer $is_sale
 * @property string $version
 * @property integer $mch_id
 * @property string $integral
 * @property string $goods_id
 * @property string $words
 * @property integer $is_recycle
 * @property integer $is_show
 */
class IntegralOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%integral_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'order_no', 'integral', 'goods_id'], 'required'],
            [['store_id', 'user_id', 'is_pay', 'pay_type', 'pay_time', 'is_send', 'send_time', 'is_confirm', 'confirm_time', 'is_comment', 'apply_delete', 'addtime', 'is_delete', 'is_offline', 'clerk_id', 'is_cancel', 'shop_id', 'is_sale', 'mch_id', 'integral', 'goods_id', 'is_recycle', 'is_show'], 'integer'],
            [['total_price', 'pay_price', 'express_price'], 'number'],
            [['address_data', 'offline_qrcode', 'words'], 'string'],
            [['order_no', 'name', 'mobile', 'express', 'express_no', 'version'], 'string', 'max' => 255],
            [['address', 'remark'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'user_id' => 'User ID',
            'order_no' => 'Order No',
            'total_price' => 'Total Price',
            'pay_price' => 'Pay Price',
            'express_price' => 'Express Price',
            'name' => 'Name',
            'mobile' => 'Mobile',
            'address' => 'Address',
            'remark' => 'Remark',
            'is_pay' => 'Is Pay',
            'pay_type' => 'Pay Type',
            'pay_time' => 'Pay Time',
            'is_send' => 'Is Send',
            'send_time' => 'Send Time',
            'express' => 'Express',
            'express_no' => 'Express No',
            'is_confirm' => 'Is Confirm',
            'confirm_time' => 'Confirm Time',
            'is_comment' => 'Is Comment',
            'apply_delete' => 'Apply Delete',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
            'address_data' => 'Address Data',
            'is_offline' => 'Is Offline',
            'clerk_id' => 'Clerk ID',
            'is_cancel' => 'Is Cancel',
            'offline_qrcode' => 'Offline Qrcode',
            'shop_id' => 'Shop ID',
            'is_sale' => 'Is Sale',
            'version' => 'Version',
            'mch_id' => 'Mch ID',
            'integral' => 'Integral',
            'goods_id' => 'goods_id',
            'words' => 'words',
            'is_recycle' => '是否加入回收站 0--不加入 1--加入',
            'is_show' => '是否显示 0--不显示 1--显示（软删除）',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id'=>'user_id']);
    }

    public function getDetail()
    {
        return $this->hasOne(IntegralOrderDetail::className(), ['order_id'=>'id']);
    }

    public function getShop()
    {
        return $this->hasOne(Shop::className(), ['id'=>'shop_id']);
    }

    public function getClerk()
    {
        return $this->hasOne(User::className(), ['id'=>'clerk_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, $this->is_delete, $data, $this->id);
    }

    public function getOrderDetail()
    {
        return $this->hasMany(IntegralOrderDetail::className(), ['order_id'=>'id'])->alias('od')
            ->leftJoin(['g'=>IntegralGoods::tableName()], 'g.id=goods_id')->select(['od.*','g.name','g.attr goods_attr']);
    }

    public static function find()
    {
        return Yii::createObject(MyActiveQuery::className(), [
            get_called_class(), [
                'myCondition' => [
                    'is_show' => 1,
                    'is_recycle'=>0
                ]
            ]
        ]);
    }
}
