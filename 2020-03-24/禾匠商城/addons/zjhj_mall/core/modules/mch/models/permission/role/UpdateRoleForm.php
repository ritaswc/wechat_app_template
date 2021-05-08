<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models\permission\role;

use app\models\AuthRole;
use app\models\AuthRolePermission;
use app\models\User;
use app\modules\mch\models\MchModel;
use Yii;

class UpdateRoleForm extends MchModel
{

    public $roleId;
    public $name;
    public $description;
    public $role;

    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['role', 'creator_id'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '角色名称',
            'description' => '描述',
        ];
    }

    public function update()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $role = AuthRole::find()->where(['store_id' => $this->getCurrentStoreId(), 'name' => $this->name])->one();
        if ($role && $this->roleId != $role->id) {
            return [
                'code' => 1,
                'msg' => '角色名称已被系统使用！请重新输入'
            ];
        }

        $transaction = Yii::$app->db->beginTransaction();

        $model = AuthRole::findOne($this->roleId);
        $model->attributes = $this->attributes;

        if ($model->save()) {
            $this->updateRolePermission();

            $transaction->commit();
            $this->clearRoleCache();
            return [
                'code' => 0,
                'msg' => '更新成功'
            ];
        }

        $transaction->rollBack();
        return $this->getErrorResponse($model);
    }

    public function updateRolePermission()
    {
        AuthRolePermission::deleteAll(['role_id' => $this->roleId]);

        if (!empty($this->role)) {
            $attributes = [];
            $role = json_decode($this->role, true);
            foreach ($role as $item) {
                $attributes[] = [
                    $this->roleId, $item,
                ];
            }
            $query = Yii::$app->db->createCommand();
            $insert = $query->batchInsert(AuthRolePermission::tableName(), ['role_id', 'permission_name'], $attributes)->execute();

            if (!$insert) {
                return $this->getErrorResponse($insert);
            }

            return true;
        }

        return false;
    }

    /**
     * 修改角色后，清除相应商城的用户菜单缓存
     */
    public function clearRoleCache()
    {
        $users = User::find()->andWhere(['store_id' => $this->getCurrentStoreId(), 'type' => User::USER_TYPE_ROLE])->all();

        foreach ($users as $item) {
            $cacheKey = 'mch-' . $this->getCurrentStoreId() . $item['access_token'];
            Yii::$app->getCache()->delete($cacheKey);
        }
    }
}
