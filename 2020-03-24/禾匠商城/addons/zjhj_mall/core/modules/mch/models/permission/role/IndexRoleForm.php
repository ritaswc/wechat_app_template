<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models\permission\role;

use app\models\AuthRole;
use app\models\User;
use app\modules\mch\models\MchModel;
use app\modules\mch\models\permission\user\EditAdminUserForm;
use yii\data\Pagination;

class IndexRoleForm extends MchModel
{
    public $limit;
    public $page;


    public function rules()
    {
        return [
            [['limit', 'page'], 'integer'],
            [['page'], 'default', 'value' => 1],
            [['limit'], 'default', 'value' => 20],
        ];
    }

    public function pagination()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $model = AuthRole::find()->andWhere(['store_id' => $this->getCurrentStoreId()]);
        $pagination = new Pagination(['totalCount' => $model->count(), 'pageSize' => $this->limit]);

        $type = $this->getCurrentUser()->identity->type;
        if ($type === User::USER_TYPE_ROLE) {
            $model->andWhere(['creator_id' => $this->getCurrentUserId()]);
        }

        $list = $model->with('user')->limit($this->limit)->offset($pagination->offset)->all();

        return [
            'list' => $list,
            'pagination' => $pagination
        ];
    }

    public function getList()
    {
        $model = AuthRole::find();

        $type = $this->getCurrentUser()->identity->type;
        if ($type === User::USER_TYPE_ROLE) {
            $model->andWhere(['creator_id' => $this->getCurrentUserId()]);
        }
        $list = $model->andWhere(['store_id' => $this->getCurrentStoreId()])->all();

        return $list;
    }

    public function getRoleByUser($userId)
    {
        $model = new EditAdminUserForm();
        $model->userId = $userId;
        $show = $model->edit();

        $roleList = self::getList();

        foreach ($roleList as $item) {
            foreach ($show->roleUser as $i) {
                if ($i->role_id == $item->id) {
                    $item->checked = true;
                    break;
                } else {
                    $item->checked = false;
                }
            }
        }

        return $roleList;
    }
}
