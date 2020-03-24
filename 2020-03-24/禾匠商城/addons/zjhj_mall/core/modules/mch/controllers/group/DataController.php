<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/6
 * Time: 11:50
 */

namespace app\modules\mch\controllers\group;

use app\modules\mch\models\group\DataGoodsForm;

class DataController extends Controller
{
    public function actionGoods()
    {
        $form = new DataGoodsForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $arr = $form->search();
        return $this->render('goods', [
            'list'=>$arr['list'],
            'pagination'=>$arr['pagination'],
            'row_count'=>$arr['row_count']
        ]);
    }
    public function actionUser()
    {
        $form = new DataGoodsForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $arr = $form->user_search();
        return $this->render('user', [
            'list'=>$arr['list'],
            'pagination'=>$arr['pagination'],
            'row_count'=>$arr['row_count']
        ]);
    }
}
