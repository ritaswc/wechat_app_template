<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%cat}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $parent_id
 * @property string $name
 * @property string $pic_url
 * @property integer $sort
 * @property integer $addtime
 * @property integer $is_delete
 * @property string $big_pic_url
 * @property string $advert_pic
 * @property string $advert_url
 * @property string $is_show
 */
class Cat extends \yii\db\ActiveRecord
{
    /**
     * 分类是否显示：显示
     */
    const IS_SHOW_TRUE = 1;

    /**
     * 分类是否显示：不显示
     */
    const IS_SHOW_FALSE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'name',], 'required'],
            [['store_id', 'parent_id', 'sort', 'addtime', 'is_delete', 'is_show'], 'integer'],
            [['pic_url', 'big_pic_url', 'advert_pic', 'advert_url'], 'string'],
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
            'store_id' => '商城id',
            'parent_id' => '上级分类id',
            'name' => '分类名称',
            'pic_url' => '分类图片url',
            'sort' => '排序，升序',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
            'big_pic_url' => '分类大图',
            'advert_pic' => '广告图片',
            'advert_url' => '广告链接',
            'is_show' => '是否显示',
        ];
    }

    /**
     * @return array
     */
    public function saveCat()
    {
        if ($this->validate()) {
            if ($this->save(false)) {
                return [
                    'code' => 0,
                    'msg' => '成功'
                ];
            } else {
                return [
                    'code' => 1,
                    'msg' => '失败'
                ];
            }
        } else {
            return (new Model())->getErrorResponse($this);
        }
    }

    public function getParent()
    {
        return $this->hasOne(Cat::className(), ['id' => 'parent_id']);
    }

    public function getChildrenList()
    {
        return $this->hasMany(Cat::className(), ['parent_id' => 'id'])->where(['is_delete' => 0])->orderBy('sort,addtime DESC');
    }

    public function getGoodsCat()
    {
        return $this->hasMany(GoodsCat::className(), ['cat_id'=>'id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, $this->is_delete, $data, $this->id);
    }
}
