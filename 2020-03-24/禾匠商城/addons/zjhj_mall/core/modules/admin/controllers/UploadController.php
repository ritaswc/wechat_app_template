<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/27
 * Time: 10:06
 */

namespace app\modules\admin\controllers;

class UploadController extends Controller
{
    public function actionIndex()
    {
        $this->layout = false;
        return $this->render('index');
    }

    public function actionUpload()
    {
    }
}
