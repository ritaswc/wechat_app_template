<?php
namespace app\modules\mch\controllers\pond;

use app\models\Express;
use app\models\Pond;
use app\models\Coupon;
use app\modules\mch\controllers\Controller;
use app\modules\mch\models\PondForm;
use app\modules\mch\models\CardForm;
use app\modules\mch\models\CardListForm;
use app\modules\mch\models\PondLogForm;
use app\models\PondLog;
use yii\data\Pagination;
use app\models\Order;

class LogController extends Controller
{
    public function actionIndex()
    {
        $model = PondLog::find()->alias('log')
            ->where([
                'log.store_id' => $this->store->id,
            ]);
        if($type = \Yii::$app->request->get('type')){
            $model = $model->andWhere(['log.type' => $type]);  
        };
        $query = $model->with(['gift' => function ($query) {
                $query->where([
                    'store_id' => $this->store->id,
                    'is_delete' => 0
                ]);
              }])->with(['coupon' => function ($query) {
                $query->where([
                  'store_id' => $this->store->id,
                  'is_delete' => 0
                ]);
              }])->joinWith(['user' => function($query){
                
            $nickname = \Yii::$app->request->get('nickname');

            if($nickname){ 
                $query->where([
                    'user.store_id' => $this->store->id,
                    'user.is_delete' => 0,
                ])->andWhere(['like','user.nickname',$nickname]);                    
            }else{
                $query->where([
                    'user.store_id' => $this->store->id,
                    'user.is_delete' => 0,
                ]);                    
            };
        }]);

        $platform = \Yii::$app->request->get('platform');
        if (isset($platform)) {
            $query->andWhere(['user.platform' => $platform]);
        }

        $count = $query->count();

        $pagination = new Pagination(['totalCount' => $count]);

        $query = $query->limit($pagination->limit)->offset($pagination->offset);
        $list = $query->orderBy('id desc')->all();
        return $this->render('index', [
            'list' => $list,
            'pagination' => $pagination,
            // 'exportList' => \Yii::$app->serializer->encode($exportList),
            'express_list' => $this->getExpressList(),
        ]);
    }

    protected function simplifyData($data)
    {
        foreach ($data as $key => $val) {
            $newData[$key] = $val->attributes;
            if ($val->gift) {
                $newData[$key]['gift'] = $val->gift->attributes['name'];
            }
            if ($val->gift) {
                $newData[$key]['coupon'] = $val->coupon->attributes['name'];
            }
        }
        return $newData;
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
}
