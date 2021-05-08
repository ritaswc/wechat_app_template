<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/7
 * Time: 18:30
 */

namespace app\modules\mch\controllers\group;

use app\modules\mch\models\group\AdForm;

class AdController extends Controller
{
    public function actionSetting()
    {
        $form = new AdForm();
        $form->store_id = $this->store->id;
        if (\Yii::$app->request->isPost) {
            $form->attributes = \Yii::$app->request->post();
            return $form->save();
        } else {
            return $this->render('setting', [
                'pic_list' => $form->getPicList(),
            ]);
        }
    }
}
