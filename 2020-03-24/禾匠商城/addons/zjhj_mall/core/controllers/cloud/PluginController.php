<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.luweiss.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/27 15:43
 */


namespace app\controllers\cloud;


use app\behaviors\CloudBehavior;
use app\hejiang\cloud\CloudPlugin;

class PluginController extends CloudController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'cloudBehavior' => [
                'class' => CloudBehavior::className(),
            ],
        ]);
    }

    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            try {
                return CloudPlugin::getList();
            } catch (\Exception $e) {
                return [
                    'code' => 1,
                    'msg' => $e->getMessage(),
                ];
            }
        } else {
            return $this->render('index');
        }
    }

    public function actionDetail()
    {
        if (\Yii::$app->request->isAjax) {
            try {
                return CloudPlugin::getDetail(\Yii::$app->request->get('id'));
            } catch (\Exception $e) {
                return [
                    'code' => 1,
                    'msg' => $e->getMessage(),
                ];
            }
        } else {
            return $this->render('detail');
        }
    }

    public function actionCreateOrder()
    {
        if (\Yii::$app->request->isPost) {
            try {
                return CloudPlugin::createOrder(\Yii::$app->request->post('id'));
            } catch (\Exception $e) {
                return [
                    'code' => 1,
                    'msg' => $e->getMessage(),
                ];
            }
        }
    }

    public function actionInstall($id)
    {
        try {
            return CloudPlugin::install($id);
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
            ];
        }
    }
}
