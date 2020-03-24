<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%address}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property string $name
 * @property string $mobile
 * @property integer $province_id
 * @property string $province
 * @property integer $city_id
 * @property string $city
 * @property integer $district_id
 * @property string $district
 * @property string $detail
 * @property integer $is_default
 * @property integer $addtime
 * @property integer $is_delete
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     *  默认状态：默认地址
     */
    const DEFAULT_STATUS_TRUE = 1;

    /**
     *  默认状态：非默认地址
     */
    const DEFAULT_STATUS_FALSE = 0;

    /**
     * 删除状态：已删除
     */
    const DELETE_STATUS_TRUE = 1;

    /**
     * 删除状态：未删除
     */
    const DELETE_STATUS_FALSE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%address}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'name', 'mobile', 'province', 'city', 'district', 'detail'], 'required'],
            [['store_id', 'user_id', 'province_id', 'city_id', 'district_id', 'is_default', 'addtime', 'is_delete'], 'integer'],
            [['name', 'mobile', 'province', 'city', 'district'], 'string', 'max' => 255],
            [['detail'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'store_id'    => 'Store ID',
            'user_id'     => 'User ID',
            'name'        => '姓名',
            'mobile'      => '手机号',
            'province_id' => 'Province ID',
            'province'    => '省份名称',
            'city_id'     => 'City ID',
            'city'        => '城市名称',
            'district_id' => 'District ID',
            'district'    => '县区名称',
            'detail'      => '详细地址',
            'is_default'  => '是否是默认地址：0=否，1=是',
            'addtime'     => 'Addtime',
            'is_delete'   => 'Is Delete',
        ];
    }

    public function beforeSave($insert)
    {
        $this->name = Html::encode($this->name);
        $this->mobile = Html::encode($this->mobile);
        $this->detail = Html::encode($this->detail);
        return parent::beforeSave($insert);
    }
}
