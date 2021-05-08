<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/3/24
 * Time: 13:56
 */


namespace app\modules\api\controllers\mch;

use app\hejiang\BaseApiResponse;
use app\modules\api\models\mch\OrderDetailForm;
use app\modules\api\models\mch\OrderEditPriceForm;
use app\modules\api\models\mch\OrderListForm;
use app\modules\api\models\mch\OrderRefundDetailForm;
use app\modules\api\models\mch\OrderRefundForm;
use app\modules\api\models\mch\OrderSendForm;

class OrderController extends Controller
{
    public function actionList()
    {
        $form = new OrderListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->mch_id = $this->mch->id;
        return new BaseApiResponse($form->search());
    }

    public function actionDetail()
    {
        $form = new OrderDetailForm();
        $form->attributes = \Yii::$app->request->get();
        $form->mch_id = $this->mch->id;
        return new BaseApiResponse($form->search());
    }

    public function actionEditPrice()
    {
        $form = new OrderEditPriceForm();
        $form->attributes = \Yii::$app->request->post();
        $form->mch_id = $this->mch->id;
        return new BaseApiResponse($form->save());
    }

    public function actionSend()
    {
        $form = new OrderSendForm();
        $form->attributes = \Yii::$app->request->post();
        $form->mch_id = $this->mch->id;
        return new BaseApiResponse($form->save());
    }

    public function actionRefundDetail()
    {
        $form = new OrderRefundDetailForm();
        $form->attributes = \Yii::$app->request->get();
        $form->mch_id = $this->mch->id;
        return new BaseApiResponse($form->search());
    }

    public function actionRefund()
    {
        $form = new OrderRefundForm();
        $form->attributes = \Yii::$app->request->post();
        $form->mch_id = $this->mch->id;
        return new BaseApiResponse($form->save());
    }
}
