<?php

/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\controllers;

use app\controllers\Controller;

class ErrorController extends Controller
{
    public function actionPermissionError()
    {
        return $this->render('permission-error');
    }
}
