<?php
namespace app\modules\mch\controllers\scratch;

use app\models\Express;
use app\modules\mch\controllers\Controller;
use app\models\ScratchLog;
use yii\data\Pagination;
use app\models\Order;
use app\models\User;

class LogController extends Controller
{
    public function actionIndex()
    {
        $model = ScratchLog::find()->alias('log')
            ->where(['log.store_id' => $this->store->id])
            ->andWhere(['<>','log.status',0]);
        if($type = \Yii::$app->request->get('type')){
            $model = $model->andWhere(['log.type' => $type]);  
        };

        $query = $model
              ->with(['gift' => function ($query){
                $query->where([
                    'store_id' => $this->store->id,
                    'is_delete' => 0
                ]);
            }])->with(['coupon' => function($query){
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
            'express_list' => $this->getExpressList(),
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
            foreach($expressLst as $value){
                if($value['name'] == $item['express']){
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