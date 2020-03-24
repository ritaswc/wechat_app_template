<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/15
 * Time: 13:36
 */

namespace app\modules\api\controllers;

use app\models\Option;
use app\models\Order;
use app\models\Recharge;
use app\models\RechargeModule;
use app\models\ReOrder;
use app\modules\api\behaviors\LoginBehavior;
use app\modules\api\models\recharge\DetailForm;
use app\modules\api\models\recharge\OrderSubmit;
use app\modules\api\models\recharge\RecordForm;

class RechargeController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginBehavior::className(),
            ],
        ]);
    }
    public function actionIndex()
    {
        $user = \Yii::$app->user->identity;

        //搜索指定月份的充值记录及余额消费记录
        $form = new RecordForm();
        $form->user = $user;
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $res = $form->search();

        if ($res->code == 1) {
            return $res;
        }

        //余额页设置
        $form = new RechargeModule();
        $form->store_id = $this->store->id;
        $setting = $form->search_recharge();

        $data = [
            'money' => $user->money,
            'list' => $res['data']['list'],
            'setting' => $setting,
            'date' => $res['data']['date'],
        ];
        return new \app\hejiang\ApiResponse(0, '', $data);
    }

    public function actionRecord()
    {
        $user = \Yii::$app->user->identity;

        //搜索指定月份的充值记录及余额消费记录
        $form = new RecordForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user = $user;
        $res = $form->search();
        return $res;
    }

    public function actionList()
    {
        $balance = Option::get('re_setting', $this->store_id, 'app');
        $balance = json_decode($balance, true);
        $list = Recharge::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])
            ->orderBy(['pay_price' => SORT_DESC])->asArray()->all();
        $data = [
            'list' => $list,
            'balance'=>$balance
        ];
        return new \app\hejiang\ApiResponse(0, '', $data);
    }

    /**
     * 充值提交
     */
    public function actionSubmit()
    {
        $form = new OrderSubmit();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        $form->attributes = \Yii::$app->request->post();
        return new \app\hejiang\BaseApiResponse($form->save());
    }

    /**
     * 余额收支详情
     */
    public function actionDetail()
    {
        $form = new DetailForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        return new \app\hejiang\BaseApiResponse($form->search());
    }
}
