<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models\permission\role;

use app\models\AuthRole;
use app\modules\mch\models\MchModel;

class EditRoleForm extends MchModel
{
    public $roleId;

    public function edit()
    {
        $edit = AuthRole::find()->andWhere(['id' => $this->roleId])->one();

        return $edit;
    }
}
