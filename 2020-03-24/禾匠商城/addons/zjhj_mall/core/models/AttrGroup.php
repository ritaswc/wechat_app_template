<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%attr_group}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $attr_group_name
 * @property integer $is_delete
 * @property Attr[] $attr
 */
class AttrGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attr_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'attr_group_name'], 'required'],
            [['store_id', 'is_delete'], 'integer'],
            [['attr_group_name'], 'string', 'max' => 255],
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
            'attr_group_name' => 'Attr Group Name',
            'is_delete' => 'Is Delete',
        ];
    }

    private $attrList;

    public function getAttrList()
    {
        if ($this->attrList) {
            return $this->attrList;
        }
        $this->attrList = Attr::findAll(['is_delete' => 0, 'attr_group_id' => $this->id]);
        return $this->attrList;
    }

    public function getAttr()
    {
        return $this->hasMany(Attr::className(), ['attr_group_id' => 'id']);
    }
}
