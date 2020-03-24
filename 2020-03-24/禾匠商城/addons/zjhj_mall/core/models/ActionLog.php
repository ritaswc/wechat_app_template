<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%action_log}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $addtime
 * @property string $admin_name
 * @property integer $admin_id
 * @property string $admin_ip
 * @property string $route
 * @property string $action_type
 * @property integer $obj_id
 * @property string $result
 * @property integer $store_id
 * @property integer $is_delete
 * @property integer $type
 */
class ActionLog extends \yii\db\ActiveRecord
{
    /**
     * 日志启用状态：关闭
     */
    const OPTION_SWITCH_OFF = 'off';

    /**
     * 日志启用状态：开启
     */
    const OPTION_SWITCH_ON = 'on';

    /**
     * 日志表 name字段
     */
    const OPTION_NAME = 'action_log';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%action_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['addtime', 'admin_id', 'action_type', 'obj_id', 'store_id'], 'required'],
            [['addtime', 'admin_id', 'obj_id', 'store_id', 'is_delete', 'type'], 'integer'],
            [['action_type', 'result'], 'string'],
            [['title', 'admin_name'], 'string', 'max' => 45],
            [['admin_ip', 'route'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'addtime' => 'Addtime',
            'admin_name' => 'Admin Name',
            'admin_id' => 'Admin ID',
            'admin_ip' => 'Admin Ip',
            'route' => 'Route',
            'action_type' => 'Action Type',
            'obj_id' => 'Obj ID',
            'result' => 'Result',
            'store_id' => 'Store ID',
            'is_delete' => 'Is Delete',
        ];
    }

    /**
     * @param $errorInfo array 错误信息
     * @param $storeId integer 商城ID
     * @param $actionType string 日志描述
     * @param $objId integer 错误信息产生对应表的ID
     * @return bool
     */
    public static function addTask($errorInfo, $storeId, $actionType, $objId)
    {
        $actionLog = new ActionLog();

        $actionLog->store_id = $storeId;
        $actionLog->title = "定时任务";
        $actionLog->addtime = time();
        $actionLog->admin_name = "系统自身";
        $actionLog->admin_id = 0;
        $actionLog->admin_ip = '';
        $actionLog->route = '';
        $actionLog->action_type = $actionType;
        $actionLog->obj_id = $objId;
        $actionLog->result = json_encode($errorInfo, JSON_UNESCAPED_UNICODE);
        $actionLog->type = 1;
        return $actionLog->save();
    }
}
