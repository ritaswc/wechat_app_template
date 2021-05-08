<?php

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/30
 * Time: 10:22
 */

namespace app\controllers;

use app\models\SystemInstallForm;
use yii\web\HttpException;

class InstallController extends Controller
{
    public function actionIndex()
    {
        throw new HttpException(403);
        return;
        $install_lock_file = \Yii::$app->basePath . '/install.lock.php';
        if (file_exists($install_lock_file)) {
            \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl(['admin']))->send();
            \Yii::$app->end();
        }
        $model = [];
        if (\Yii::$app->request->isPost) {
            $form = new SystemInstallForm();
            $form->attributes = \Yii::$app->request->post('model');
            $res = $form->install();
            return $this->asJson($res);
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }
}
