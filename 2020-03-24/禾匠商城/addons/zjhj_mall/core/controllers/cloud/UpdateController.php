<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.luweiss.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/12/1 10:46
 */


namespace app\controllers\cloud;


use app\behaviors\CloudBehavior;
use app\hejiang\cloud\CloudUpdate;
use app\hejiang\cloud\forms\UpdateForm;

class UpdateController extends CloudController
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
                return CloudUpdate::getData();
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

    public function actionUpdate()
    {
        $form = new UpdateForm();
        $form->attributes = \Yii::$app->request->post();
        return $form->update();
    }
}
