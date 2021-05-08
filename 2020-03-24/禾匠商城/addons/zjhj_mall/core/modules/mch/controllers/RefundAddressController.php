<?php
namespace app\modules\mch\controllers;

use app\models\RefundAddress;
use app\modules\mch\models\RefundAddressForm;
use yii\data\Pagination;

class RefundAddressController extends Controller
{
    public function actionIndex()
    {
        $query = RefundAddress::find()->where(['store_id' => $this->store->id, 'is_delete' => 0]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count]);
        $list = $query->limit($pagination->limit)->orderBy('id DESC')->offset($pagination->offset)->all();

        return $this->render('index', [
            'list' => $list,
            'pagination' => $pagination,
        ]);
    }

    public function actionEdit($id = null)
    {
        $model = RefundAddress::findOne(['id' => $id, 'is_delete' => 0,'store_id'=>$this->store->id]);
        if (!$model) {
            $model = new RefundAddress();
        }
        if (\Yii::$app->request->isPost) {
            $form = new RefundAddressForm();
            $form->model = $model;
            $form->store_id = $this->store->id;
            $form->attributes = \Yii::$app->request->post();
            return $form->save();
        }
        return $this->render('edit', [
            'model' => $model
        ]);
    }

    public function actionDel($id = null)
    {
        $model = RefundAddress::findOne(['id' => $id, 'is_delete' => 0,'store_id'=>$this->store->id]);
        if (!$model) {
            return [
                'code'=> 1 ,
                'msg'=>'请刷新重试'
            ];
        }
        $model->is_delete = 1;
        if ($model->save()) {
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
        } else {
            foreach ($model->errors as $errors) {
                return [
                    'code' => 1,
                    'msg' => $errors[0],
                ];
            }
        }
    }

}
