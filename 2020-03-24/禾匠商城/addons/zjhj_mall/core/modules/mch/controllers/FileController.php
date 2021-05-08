<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/12
 * Time: 16:01
 */

namespace app\modules\mch\controllers;

use app\models\UploadFile;
use app\modules\mch\models\FileForm;

class FileController extends Controller
{
    //获取分组数组
    public function actionGroup()
    {
        $form = new FileForm();
        $form->store_id = $this->store->id;
        $group_list = $form->getData();
        return $group_list;
    }

    //保存分组数组
    public function actionEditGroup()
    {
        $form = new FileForm();
        $form->store_id = $this->store->id;
        $group = \Yii::$app->request->post('data');
        $form->group = $group;
        return $form->saveData();
    }

    //删除图片
    public function actionDelete()
    {
        $file = \Yii::$app->request->post('data');
        $file = json_decode($file, true);
        $file_id_list = [];
        foreach ($file as $index => $value) {
            $file_id_list[] = $value['id'];
        }
        UploadFile::updateAll(['is_delete'=>1], ['store_id'=>$this->store->id,'id'=>$file_id_list]);
        return ['code'=>0,'msg'=>'成功'];
    }

    //图片移动到某个组
    public function actionMove()
    {
        $file = \Yii::$app->request->post('data');
        $group_id = \Yii::$app->request->post('group_id');
        $file = json_decode($file, true);
        $file_id_list = [];
        foreach ($file as $index => $value) {
            $file_id_list[] = $value['id'];
        }
        if ($group_id <= 0) {
            $group_id = 0;
        }
        UploadFile::updateAll(['group_id'=>$group_id], ['store_id'=>$this->store->id,'id'=>$file_id_list]);
        return ['code'=>0,'msg'=>'成功'];
    }

    //编辑当个分组
    public function actionEditGroupOne()
    {
        $form = new FileForm();
        $form->store_id = $this->store->id;
        $group = \Yii::$app->request->post('data');
        $form->group = $group;
        return $form->save();
    }
}
