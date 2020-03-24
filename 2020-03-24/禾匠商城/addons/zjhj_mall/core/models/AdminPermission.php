<?php

namespace app\models;


/**
 * This is the model class for table "{{%admin_permission}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $display_name
 * @property integer $is_delete
 * @property integer $sort
 */
class AdminPermission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_permission}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['display_name'], 'required'],
            [['is_delete', 'sort'], 'integer'],
            [['name', 'display_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'display_name' => 'Display Name',
            'is_delete' => 'Is Delete',
            'sort' => 'Sort',
        ];
    }

    private static $list;

    /**
     * 获取所有权限列表
     * @return AdminPermission[]
     */
    public static function getList()
    {
        if (self::$list) {
            return self::$list;
        }
        self::$list = self::find()->where(['is_delete' => 0])->orderBy('sort ASC,id ASC')->all();
        $plugin_list = \app\hejiang\cloud\CloudPlugin::getInstallPluginList();
        foreach ($plugin_list as $plugin) {
            self::$list[] = (object)[
                'id' => null,
                'name' => $plugin['name'],
                'display_name' => $plugin['value']['display_name'],
                'is_delete' => 0,
                'sort' => 1000,
            ];
        }
        return self::$list;
    }
}
