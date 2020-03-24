<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models\permission\user;


use app\models\Model;
use app\models\User;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;

class IndexAdminUserForm extends MchModel
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

        $model = User::find()->andWhere(['type' => User::USER_TYPE_ROLE, 'store_id' => $this->getCurrentStoreId(), 'is_delete' => Model::IS_DELETE_FALSE]);
        $pagination = new Pagination(['totalCount' => $model->count(), 'pageSize' => $this->limit]);

        $type = $this->getCurrentUser()->identity->type;
        if ($type === User::USER_TYPE_ROLE) {
            $model->andWhere(['parent_id' => $this->getCurrentUserId()]);
        }

        $list = $model->with('creator')->limit($this->limit)->offset($pagination->offset)->all();

        return [
            'adminUrl' => $this->getAdminUrl('role'),
            'list' => $list,
            'pagination' => $pagination
        ];
    }
}
