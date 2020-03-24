<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/5/4
 * Time: 16:43
 */


namespace app\modules\user\controllers\mch;

use app\modules\user\behaviors\MchBehavior;
use app\modules\user\behaviors\UserLoginBehavior;
use app\modules\user\controllers\Controller;
use app\modules\user\models\mch\CashListForm;
use app\modules\user\models\mch\CashSubmitForm;
use app\modules\user\models\mch\LogListForm;

class AccountController extends Controller
{
    public function behaviors()
    {
        return [
            'login' => [
                'class' => UserLoginBehavior::className(),
            ],
            'mch' => [
                'class' => MchBehavior::className(),
            ],
        ];
    }

    public function actionCash()
    {
        if (\Yii::$app->request->isPost) {
            $form = new CashSubmitForm();
            $form->attributes = \Yii::$app->request->post();
            $form->mch_id = $this->mch->id;
            return $form->save();
        } else {
            $form = new CashListForm();
            $form->attributes = \Yii::$app->request->get();
            $form->mch_id = $this->mch->id;
            $form->store_id = $this->store->id;
            $res = $form->search();
            return $this->render('cash', [
                'list' => $res['data']['list'],
                'pagination' => $res['data']['pagination'],
                'account_money' => $this->mch->account_money,
                'type_list'=>\Yii::$app->serializer->encode($form->getSetting())
            ]);
        }
    }

    public function actionLog()
    {
        $form = new LogListForm();
        $form->store_id = $this->store->id;
        $form->mch_id = $this->mch->id;
        $form->attributes = \Yii::$app->request->get();
        $arr = $form->search();
        return $this->render('log', [
            'list' => $arr['list'],
            'pagination' => $arr['pagination']
        ]);
    }
}
