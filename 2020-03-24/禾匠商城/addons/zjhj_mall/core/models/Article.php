<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%article}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $article_cat_id
 * @property string $title
 * @property string $content
 * @property integer $sort
 * @property integer $addtime
 * @property integer $is_delete
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'article_cat_id', 'sort', 'addtime', 'is_delete'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255],
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
            'article_cat_id' => '分类id：1=关于我们，2=服务中心',
            'title' => '标题',
            'content' => '内容',
            'sort' => '排序：升序',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, $this->is_delete, $data, $this->id);
    }
}
