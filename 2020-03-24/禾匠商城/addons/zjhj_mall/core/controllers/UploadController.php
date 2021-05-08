<?php

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/8
 * Time: 23:40
 */

namespace app\controllers;

use app\models\Order;
use app\models\Store;
use app\models\UploadConfig;
use app\models\UploadForm;
use app\models\User;
use yii\db\Query;

set_time_limit(0);

class UploadController extends Controller
{
    /* @var Store $store */
    public $store;

    /* @var UploadConfig $upload_config */
    public $upload_config;

    public function behaviors()
    {
        $store_id = \Yii::$app->session->get('store_id');
        if ($store_id) {
            $this->store = Store::findOne($store_id);
            $this->upload_config = UploadConfig::findOne(['store_id' => 0, 'is_delete' => 0]);
        }
        $this->enableCsrfValidation = false;
        return parent::behaviors();
    }

    public function actionImage($name = null)
    {
        $form = new UploadForm();
        $form->upload_config = $this->upload_config;
        $form->store = $this->store;
        $form->group_id = \Yii::$app->request->get('group_id');
        $form->mch_id = \Yii::$app->request->get('mch_id');
        return $form->saveImage($name);
    }

    public function actionFile($name = null)
    {
        $form = new UploadForm();
        $form->upload_config = $this->upload_config;
        $form->store = $this->store;
        $form->group_id = \Yii::$app->request->get('group_id');
        $form->mch_id = \Yii::$app->request->get('mch_id');
        return $form->saveImage($name);
    }

    //ue专用
    public function actionUe($action)
    {
        $config = [
            'imageActionName' => 'uploadimage',
            'imageFieldName' => 'image',
            "imageCompressEnable" => true,
            'imageAllowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],
            'imageMaxSize' => 1024 * 1024 * 8,
            'imageUrlPrefix' => '',
        ];
        switch ($action) {
            case 'config':
                {
                    echo json_encode($config, JSON_UNESCAPED_UNICODE);
                    break;
            }
            case $config['imageActionName']:
                {
                    $form = new UploadForm();
                    $form->upload_config = $this->upload_config;
                    $form->store = $this->store;
                    $res = $form->saveImage($config['imageFieldName']);
                if ($res['code'] == 0) {
                    echo json_encode([
                        'state' => 'SUCCESS',
                        'url' => $res['data']['url'],
                    ], JSON_UNESCAPED_UNICODE);
                } else {
                    echo json_encode([
                        'state' => $res['msg'],
                        'url' => $res['data']['url'],
                    ], JSON_UNESCAPED_UNICODE);
                }
                break;
            }
            default:
                break;
        }
    }

    public function actionVideo($name = null)
    {
        set_time_limit(0);
        $form = new UploadForm();
        $form->upload_config = $this->upload_config;
        $form->store = $this->store;
        return $form->saveVideo($name);
    }

    // 上传文件于缓存目录
    public function actionTempFile($name = null)
    {
        $form = new UploadForm();
        $form->upload_config = $this->upload_config;
        $form->store = $this->store;
        return $form->saveFileTemp($name);
    }
}
