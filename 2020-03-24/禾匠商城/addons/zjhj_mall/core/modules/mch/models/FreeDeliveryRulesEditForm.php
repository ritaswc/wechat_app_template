<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/3
 * Time: 14:37
 */

namespace app\modules\mch\models;

use app\models\FreeDeliveryRules;

/**
 * @property FreeDeliveryRules $postage_rules
 */
class FreeDeliveryRulesEditForm extends MchModel
{
    public $free_delivery_rules;

    public $price;
    public $city;

    public function rules()
    {
        return [
            [['price','city',], 'required'],
            [['price'],'number','min' => 0],
        ];
    }

    public function save()
    {

        if (!$this->validate()) {
            return $this->errorResponse;
        }
        
        $this->free_delivery_rules->attributes = $this->attributes;
        $this->free_delivery_rules->city = $this->city ? \Yii::$app->serializer->encode($this->city) : '';
        if ($this->free_delivery_rules->save()) {
            return [
                'code' => 0,
                'msg' => '保存成功'
            ];
        } else {
            return $this->getErrorResponse($this->free_delivery_rules);
        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'price' => '包邮金额',
            'city' => '城市',
        ];
    }
}
