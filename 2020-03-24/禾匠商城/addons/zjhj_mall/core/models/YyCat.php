<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%yy_cat}}".
 *
 * @property string $id
 * @property string $name
 * @property string $store_id
 * @property string $pic_url
 * @property integer $sort
 * @property string $addtime
 * @property integer $is_delete
 */
class YyCat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%yy_cat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'store_id'], 'required'],
            [['store_id', 'sort', 'addtime', 'is_delete'], 'integer'],
            [['pic_url'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '标题名称',
            'store_id' => '商城ID',
            'pic_url' => '分类图片url',
            'sort' => '排序 升序',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
        ];
    }
}
