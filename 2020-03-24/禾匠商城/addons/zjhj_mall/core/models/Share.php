<?php

namespace app\models;

use Yii;
use Codeception\PHPUnit\ResultPrinter\HTML;

/**
 * This is the model class for table "{{%share}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $mobile
 * @property string $name
 * @property integer $status
 * @property integer $is_delete
 * @property integer $addtime
 * @property integer $store_id
 */
class Share extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%share}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'store_id'], 'required'],
            [['user_id', 'status', 'is_delete', 'addtime', 'store_id'], 'integer'],
            [['mobile', 'name'], 'string', 'max' => 255],
            [['seller_comments'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'mobile' => 'Mobile',
            'name' => 'Name',
            'status' => '审核状态 0--未审核 1--审核通过 2--审核不通过',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
            'store_id' => '商城id',
            'seller_comments' => '备注',
        ];
    }
    // public function saveS()
    // {
    //     if($this->save()){
    //         return [
    //             'code'=>0,
    //             'msg'=>'成功'
    //         ];
    //     }else{
    //         return [
    //             'code'=>1,
    //             'msg'=>'网络异常',
    //             'data'=>$this->errors,
    //         ];
    //     }
    // }
    public function getFirstChildren()
    {
        return $this->hasMany(User::className(), ['parent_id'=>'user_id'])
            ->alias('f')->andWhere(['f.is_delete'=>0,'f.type' => 1])->select(['f.id','f.nickname','f.parent_id','f.addtime']);
    }
    public static function getChildren($index)
    {
        $share = Share::findOne(['user_id'=>$index]);
        if (!$share) {
            return null;
        }
        $children = $share->firstChildren;
        if (!$children) {
            return null;
        }
        return $children;
    }
    // public function getC()
    // {
    //     return $this->hasMany(User::className(),['parent_id'=>'user_id']);
    // }

    public function beforeSave($insert)
    {
        $this->name = \yii\helpers\Html::encode($this->name);
        return parent::beforeSave($insert);
    }
}
