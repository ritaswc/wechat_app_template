<?php
namespace app\modules\mch\controllers\pond;

use app\modules\mch\controllers\Controller;
use app\models\Order;
use app\models\User;
use app\modules\mch\models\order\OrderClerkForm;
use app\modules\mch\models\order\OrderDeleteForm;
use yii\data\Pagination;
use app\modules\mch\models\ExportList;
use app\models\Express;
use app\modules\mch\models\OrderListForm;
use app\modules\mch\models\OrderDetailForm;
use app\modules\mch\models\StoreDataForm;

class OrderController extends Controller
{
    public function actionIndex()
    {
        // 获取可导出数据
        $f = new ExportList();
        $exportList = $f->getList();

        $form = new OrderListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->platform = \Yii::$app->request->get('platform');
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        $form->limit = 10;
        $form->type = 1;
        $data = $form->search();

        $store_data_form = new StoreDataForm();
        $store_data_form->store_id = $this->store->id;
        $store_data_form->is_offline = \Yii::$app->request->get('is_offline');
        $user_id = \Yii::$app->request->get('user_id');
        $clerk_id = \Yii::$app->request->get('clerk_id');
        $shop_id = \Yii::$app->request->get('shop_id');
        $store_data_form->user_id = $user_id;
        $store_data_form->clerk_id = $clerk_id;
        $store_data_form->shop_id = $shop_id;
        if ($user_id) {
            $user = User::findOne(['store_id' => $this->store->id, 'id' => $user_id]);
        }
        if ($clerk_id) {
            $clerk = User::findOne(['store_id' => $this->store->id, 'id' => $clerk_id]);
        }
        if ($shop_id) {
            $shop = Shop::findOne(['store_id' => $this->store->id, 'id' => $shop_id]);
        }
        return $this->render('index', [
            'row_count' => $data['row_count'],
            'pagination' => $data['pagination'],
            'list' => $data['list'],
            //'count_data' => OrderListForm::getCountData($this->store->id),
            'store_data' => $store_data_form->search(),
            'express_list' => $this->getExpressList(),
            'user' => $user,
            'clerk' => $clerk,
            'shop' => $shop,
            'exportList' => \Yii::$app->serializer->encode($exportList)
        ]);
    }

    private function getExpressList()
    {
        $storeExpressList = Order::find()
            ->select('express')
            ->where([
                'AND',
                ['store_id' => $this->store->id],
                ['is_send' => 1],
                ['!=', 'express', ''],
            ])->groupBy('express')->orderBy('send_time DESC')->limit(5)->asArray()->all();
        $expressLst = Express::getExpressList();
        $newStoreExpressList = [];
        foreach ($storeExpressList as $i => $item) {
            foreach ($expressLst as $value) {
                if ($value['name'] == $item['express']) {
                    $newStoreExpressList[] = $item['express'];
                    break;
                }
            }
        }

        $newPublicExpressList = [];
        foreach ($expressLst as $i => $item) {
            $newPublicExpressList[] = $item['name'];
        }

        return [
            'private' => $newStoreExpressList,
            'public' => $newPublicExpressList,
        ];
    }
    private function simplifyData($data)
    {
        foreach ($data as $key => $val) {
            $newData[$key] = $val->attributes;
            if ($val->user) {
                $newData[$key]['user'] = $val->user->attributes;
            }
            if ($val->pondDetail) {
                $newData[$key]['detail'] = $val->pondDetail->attributes;
            }
            if ($val->pondGoods) {
                $newData[$key]['gift'] = $val->pondGoods->attributes;
            }
        }
        return $newData;
    }

    // 清空回收站
    public function actionDeleteAll()
    {
        $orderDeleteForm = new OrderDeleteForm();
        $orderDeleteForm->order_model = 'app\models\Order';
        $orderDeleteForm->store = $this->store;
        $orderDeleteForm->type = 1;
        return $orderDeleteForm->deleteAll();
    }

    // 核销订单
    public function actionClerk()
    {
        $form = new OrderClerkForm();
        $form->attributes = \Yii::$app->request->get();
        $form->order_model = 'app\models\Order';
        $form->order_type = 5;
        $form->store = $this->store;
        return $form->clerk();
    }
}
