<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models\permission\user;

use app\models\User;
use app\modules\mch\models\MchModel;

class EditAdminUserForm extends MchModel
{
    public $userId;

    public function edit()
    {
        $edit = User::find()->andWhere(['id' => $this->userId])->with('roleUser')->one();

        return $edit;
    }
}
