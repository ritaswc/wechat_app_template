<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models\permission\user;

use app\models\AuthRoleUser;
use app\models\User;
use app\modules\mch\models\MchModel;
use Yii;

class UpdateAdminUserForm extends MchModel
{
    public $userId;
    public $nickname;
    public $username;
    public $role;

    public function rules()
    {
        return [
            [['nickname', 'username'], 'required'],
            [['role'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'nickname' => '用户昵称',
            'username' => '用户名',
        ];
    }

    public function update()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $user = User::find()->where(['store_id' => $this->getCurrentStoreId(), 'username' => $this->username])->one();
        if ($user && $user->id != $this->userId) {
            return [
                'code' => 1,
                'msg' => '用户名已被系统使用！请重新输入'
            ];
        }
        $transaction = Yii::$app->db->beginTransaction();

        $model = User::findOne($this->userId);
        $model->attributes = $this->attributes;

        if ($model->save()) {
            $this->updateRoleUser($model->id);
            $transaction->commit();

            //更新用户权限后，删除该用户的权限缓存
            $cacheKey = $model->store_id . $model->access_token;
            Yii::$app->getCache()->delete($cacheKey);

            return [
                'code' => 0,
                'msg' => '更新成功'
            ];
        }

        $transaction->rollBack();
        return $this->getErrorResponse($model);
    }

    /**
     * 更新用户角色关联
     */
    public function updateRoleUser($userId)
    {
        AuthRoleUser::deleteAll(['user_id' => $userId]);

        if (!empty($this->role)) {
            //TODO 需要优化 会有sql注入问题
            $attributes = [];
            foreach ($this->role as $item) {
                $attributes[] = [
                    $item,
                    $userId,
                ];
            }

            $query = Yii::$app->db->createCommand();
            $insert = $query->batchInsert(AuthRoleUser::tableName(), ['role_id', 'user_id'], $attributes)->execute();

            if (!$insert) {
                return $this->getErrorResponse($insert);
            }

            return true;
        }
    }
}
