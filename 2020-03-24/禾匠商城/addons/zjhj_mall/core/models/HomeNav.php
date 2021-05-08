<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%home_nav}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property string $url
 * @property string $open_type
 * @property string $pic_url
 * @property integer $addtime
 * @property integer $sort
 * @property integer $is_delete
 * @property integer $is_hide
 */
class HomeNav extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%home_nav}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'pic_url'], 'required'],
            [['store_id', 'addtime', 'sort', 'is_delete', 'is_hide'], 'integer'],
            [['pic_url'], 'string'],
            [['name', 'open_type'], 'string', 'max' => 255],
            [['url'], 'string', 'max' => 500],
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
            'name' => '图标名称',
            'url' => '页面路径',
            'open_type' => '打开方式',
            'pic_url' => '图标url',
            'addtime' => 'Addtime',
            'sort' => '排序，升序',
            'is_delete' => 'Is Delete',
            'is_hide' => '是否隐藏 0 显示 1隐藏 ',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, $this->is_delete, $data, $this->id);
    }
}
