<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/28
 * Time: 9:14
 */

namespace app\modules\mch\controllers;

use app\models\Area;
use app\modules\mch\models\AreaForm;

/**
 * 配送管理
 * Class ExpressController
 * @package app\modules\mch\controllers
 */
class ExpressController extends Controller
{
    /**
     * 收费地区列表
     * @return string
     */
    public function actionExpress()
    {
        $form = new AreaForm();
        $list = $form->getList();
        // 获取所有省
        $province = Area::find()->andWhere(['is_delete'=>0,'level'=>2,'parent_id'=>1])->asArray()->all();

        return $this->render('express', [
            'list'=>$list[0],
            'pagination'=>$list[1],
            'province'=>$province,
        ]);
    }

    /**
     * 开启或者关闭
     * @param int $id
     * @param string $type
     * @return string|\yii\web\Response
     */
    public function actionExpressDel($id = 0)
    {
        if (\Yii::$app->request->isAjax) {
            ## 关闭
            $city = Area::findOne(['is_open' => 1, 'id' => $id]);
            if (!$id || !$city) {
                return [
                    'code' => 1,
                    'msg' => '该地区不存在，或已关闭'
                ];
            }

            /**
             * 关闭该市后检测该市上级省下面的所有市是否都关闭
             * 如果都关闭那么也将该省关闭
             */
            $citys = Area::find()->andWhere(['parent_id' => $city['parent_id'], 'is_open' => 1])->count();
            $province = Area::findOne(['id' => $city['parent_id'], 'is_open' => 1]);

            if ($city && $citys == '1') {
                $province->is_open = 0;
                $province->save();
            }

            $city->is_open = 0;
            return $city->saveArea();
        } else {
            $url = \Yii::$app->urlManager->createAbsoluteUrl(['mch/express/express']);
            return $this->redirect($url);
        }
    }

    public function actionExpressAdd()
    {
        $form = new AreaForm();

        $list = $form->getCityList();

        // 添加数据提交进入
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $city = Area::findOne(['id' => $post['city']]);
            if (!$city) {
                return [
                    'code' => 1,
                    'msg' => '该地区不存在'
                ];
            }

            /**
             * 关闭该市后检测该市上级省下面的所有市是否都关闭
             * 如果都关闭那么也将该省关闭
             */
            $citys = Area::find()->andWhere(['parent_id' => $post['province'], 'is_open' => 0])->count();
            $province = Area::findOne(['id' => $post['province'], 'is_open' => 0]);

            if ($city && $citys == '1') {
                $province->is_open = 1;
                $province->save();
            }

            $city->is_open = 1;
            $city->postage = $post['postage'];
            return $city->saveArea();
        }

        return $this->render('express-add', [
            'province'=>$list[0],
            'city'=>$list[1],
        ]);

//        $cat = Cat::findOne(['id'=>$id]);
//        if(!$cat){
//            $cat = new Cat();
//        }
//        $form = new CatForm();
//        if(\Yii::$app->request->isPost){
//            $model = \Yii::$app->request->post('model');
//            $model['store_id'] = $this->store->id;
//            $form->attributes = $model;
//            $form->cat = $cat;
//            return json_encode($form->save(),JSON_UNESCAPED_UNICODE);
//        }
//        return $this->render('good-class-edit',[
//            'list'=>$cat
//        ]);
    }

    //获取下辖城市
    public function actionExpressCity($proid = 0)
    {
        if (\Yii::$app->request->isAjax) {
            $proid = \Yii::$app->request->get('proid');

            $city = Area::find()->andWhere(['is_delete'=>0,'parent_id'=>$proid])->asArray()->all();

            return ['code'=>0,'list'=>$city];
        }
    }
}
