<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%topic}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $type
 * @property string $title
 * @property string $sub_title
 * @property string $cover_pic
 * @property string $content
 * @property integer $read_count
 * @property integer $virtual_read_count
 * @property integer $layout
 * @property integer $sort
 * @property integer $agree_count
 * @property integer $virtual_agree_count
 * @property integer $virtual_favorite_count
 * @property integer $addtime
 * @property integer $is_chosen
 * @property integer $is_delete
 * @property string $qrcode_pic
 */
class Topic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%topic}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id'], 'required'],
            [['store_id', 'type', 'read_count', 'virtual_read_count', 'layout', 'sort', 'agree_count', 'virtual_agree_count', 'virtual_favorite_count', 'addtime', 'is_chosen', 'is_delete'], 'integer'],
            [['cover_pic', 'content', 'qrcode_pic'], 'string'],
            [['title', 'sub_title'], 'string', 'max' => 255],
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
            'type' => 'Type',
            'title' => '标题',
            'sub_title' => '副标题',
            'cover_pic' => '封面图片',
            'content' => '专题内容',
            'read_count' => '阅读量',
            'virtual_read_count' => '虚拟阅读量',
            'layout' => '布局方式：0=小图，1=大图模式',
            'sort' => '排序：升序',
            'agree_count' => '点赞数',
            'virtual_agree_count' => '虚拟点赞数',
            'virtual_favorite_count' => '虚拟收藏量',
            'addtime' => 'Addtime',
            'is_chosen' => 'Is Chosen',
            'is_delete' => 'Is Delete',
            'qrcode_pic'=> '海报图',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, $this->is_delete, $data, $this->id);
    }
}
