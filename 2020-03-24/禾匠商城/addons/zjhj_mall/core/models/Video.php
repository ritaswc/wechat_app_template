<?php

namespace app\models;

use Yii;
use Codeception\PHPUnit\ResultPrinter\HTML;

/**
 * This is the model class for table "{{%video}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property string $sort
 * @property integer $is_delete
 * @property integer $addtime
 * @property integer $store_id
 * @property string $pic_url
 * @property string $content
 * @property integer $type
 */
class Video extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%video}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'pic_url', 'content'], 'string'],
            [['is_delete', 'addtime', 'store_id', 'type'], 'integer'],
            [['title', 'sort'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'url' => '路径',
            'sort' => '排序  升序',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
            'store_id' => '商城id',
            'pic_url' => 'Pic Url',
            'content' => '详情介绍',
            'type' => '视频来源 0--源地址 1--腾讯视频',
        ];
    }
    public function beforeSave($insert)
    {
        $this->title = \yii\helpers\Html::encode($this->title);
        $this->content = \yii\helpers\Html::encode($this->content);
        return parent::beforeSave($insert);
    }
}
