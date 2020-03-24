<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/28
 * Time: 9:42
 */
namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%area}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string  $name
 * @property integer $level
 * @property integer $is_delete
 * @property integer $addtime
 * @property integer $sort
 * @property integer $is_open
 * @property integer $postage
 */
class Area extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%area}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'level', 'is_delete', 'sort', 'is_open'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['postage'],'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => '上一级id',
            'name' => '地区名称',
            'level' => '地区等级：1=国家，2=省；3=城市；4=县区',
            'is_delete' => 'Is Delete',
            'sort' => '排序：升序',
            'is_open' => '是否包邮：1不包邮，0包邮',
            'postage' => '邮费',
        ];
    }
    public function saveArea()
    {
        if ($this->validate()) {
            if ($this->save()) {
                return [
                    'code'=>0,
                    'msg'=>'成功'
                ];
            } else {
                return [
                    'code'=>1,
                    'msg'=>'失败'
                ];
            }
        } else {
            return (new Model())->getErrorResponse($this);
        }
    }
    public function getChildren()
    {
        return $this->hasMany(self::className(), ['parent_id'=>'id']);
    }
}
