<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%banner}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $pic_url
 * @property string $title
 * @property string $page_url
 * @property integer $sort
 * @property integer $addtime
 * @property integer $is_delete
 * @property integer $type
 * @property string $open_type
 */
class Banner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%banner}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'pic_url', 'title'], 'required'],
            [['store_id', 'sort', 'addtime', 'is_delete', 'type'], 'integer'],
            [['pic_url', 'page_url'], 'string'],
            [['title', 'open_type'], 'string', 'max' => 255],
            ['type', 'default', 'value' => 1,],
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
            'pic_url' => '图片url',
            'title' => '标题',
            'page_url' => '页面路径',
            'sort' => '排序，升序',
            'addtime' => '添加时间',
            'is_delete' => '是否删除：0=未删除，1=已删除',
            'type' => '类型 【1=> 商城，2=> 拼团】',
            'open_type' => 'Open Type',
        ];
    }

    /**
     * @return array
     */
    public function saveBanner()
    {
        if ($this->validate()) {
            if ($this->save()) {
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

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, $this->is_delete, $data, $this->id);
    }
}
