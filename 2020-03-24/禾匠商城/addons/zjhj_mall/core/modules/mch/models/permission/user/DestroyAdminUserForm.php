<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models\permission\user;

use app\models\AuthRoleUser;
use app\models\Model;
use app\models\User;
use app\modules\mch\models\MchModel;
use Yii;

class DestroyAdminUserForm extends MchModel
{
    public $userId;

    public function destroy()
    {

        $transaction = Yii::$app->db->beginTransaction();

        $model = User::findOne($this->userId);

        if ($model) {
            $model->is_delete = Model::IS_DELETE_TRUE;
            $model->save();

            $destroyed = AuthRoleUser::deleteAll(['user_id' => $this->userId]);
            $transaction->commit();

            return [
                'code' => 0,
                'msg' => '删除成功'
            ];
        }

        $transaction->rollBack();
        return $this->getErrorResponse($model);

    }
}
