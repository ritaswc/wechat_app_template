<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models\permission\role;

use app\models\AuthRole;
use app\models\AuthRolePermission;
use app\modules\mch\models\MchModel;
use Yii;

class StoreRoleForm extends MchModel
{
    public $store_id;
    public $name;
    public $description;
    public $role;
    public $creator_id;

    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['role'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '角色名称',
            'description' => '描述',
        ];
    }

    public function store()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $role = AuthRole::find()->where(['store_id' => $this->getCurrentStoreId(), 'name' => $this->name])->one();
        if ($role) {
            return [
                'code' => 1,
                'msg' => '角色名称已被系统使用！请重新输入'
            ];
        }
        $transaction = Yii::$app->db->beginTransaction();

        $model = new AuthRole();
        $model->attributes = $this->attributes;
        $model->created_at = time();
        $model->updated_at = time();
        $model->creator_id = $this->getCurrentUserId();
        $model->store_id = $this->getCurrentStoreId();


        if ($model->save()) {
            $this->storeRolePermission($model->id);
            $transaction->commit();

            return [
                'code' => 0,
                'msg' => '添加成功'
            ];
        }

        $transaction->rollBack();
        return $this->getErrorResponse($model);
    }

    public function storeRolePermission($roleId)
    {
        if (empty($this->role)) {
            return false;
        }

        $attributes = [];
        $role = json_decode($this->role, true);
        foreach ($role as $item) {
            $attributes[] = [
                $item, $roleId,
            ];
        }
        $query = Yii::$app->db->createCommand();
        $insert = $query->batchInsert(AuthRolePermission::tableName(), ['permission_name', 'role_id'], $attributes)->execute();

        if (!$insert) {
            return $this->getErrorResponse($insert);
        }

        return true;
    }
}
