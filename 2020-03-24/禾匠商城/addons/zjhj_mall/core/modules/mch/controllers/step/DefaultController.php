<?php
namespace app\modules\mch\controllers\step;

use app\modules\mch\models\step\StepSettingForm;
use app\modules\mch\models\step\StepGoodsForm;
use app\modules\mch\models\step\StepActivityForm;
use app\modules\mch\models\BannerForm;
use app\modules\mch\models\step\AdForm;
use app\models\Banner;
use app\models\StepSetting;
use yii\data\Pagination;
use app\models\StepLog;
use app\models\StepUser;
use app\models\StepInvite;
use app\models\Pic;
use app\models\StepActivity;
use app\models\Ad;
use app\models\Option;
use app\models\ActivityMsgTpl;
use yii\db\Expression;

class DefaultController extends Controller
{
    //基础配置
    public function actionSetting()
    {

        $option = Option::getList([ 'step'], $this->store->id, 'admin');
        $model = StepSetting::findOne([
            'store_id' => $this->store->id,
        ]);
        $pic = Pic::find()->where([
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ])->all();
        if (!$model) {
            $model = new StepSetting();
        }
        if (\Yii::$app->request->isPost) {
            $form = new StepSettingForm();
            $form->attributes = \Yii::$app->request->post();
            $form->model = $model;
            $form->store_id = $this->store->id;
            return $form->save();
        };

        return $this->render('setting', [
            'setting' => $model,
            'qrcode_pic' => $pic,
            'option' => $option
        ]);
    }

    //用户列表
    public function actionUser($keyword = null)
    {
        $keyword = trim($keyword);

        $chQuery = StepUser::find()->where([
                    'store_id' => $this->store->id,
                    'is_delete' => 0,
                ])->andWhere('parent_id = s.id')->select('count(id)');

        $query = StepUser::find()->alias('s')->select(["s.*","child_num" => $chQuery])->where([
                's.store_id' => $this->store->id,
                's.is_delete' => 0,
            ])->joinwith(['user u' => function ($query) use ($keyword) {
                $query->where([
                        'AND',
                        ['u.store_id' => $this->store->id],
                        ['u.is_delete' => 0],
                        ['LIKE', 'u.nickname', $keyword],
                    ]);
            }]);

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('s.id desc')->asArray()->all();

        return $this->render('user', [
            'list' => $list,
            'pagination' => $pagination,
        ]);
    }

    //邀请记录
    public function actionInvite()
    {
        $get = \Yii::$app->request->get();
        $id = $get['id'];
        $page = $get['page'];
 
        $query = StepUser::find()->where([
            'store_id' => $this->store->id,
            'parent_id' => $id,
            'is_delete' => 0,
        ])->with('user');

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count,'page' => $page - 1, 'pageSize' => 10]);
        $invite_list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('create_time desc,id desc')->asArray()->all();

        return [
            'code' => 0,
            'invite_list' => $invite_list,
        ];
    }

    //活动列表
    public function actionActivity()
    {
        $query = StepActivity::find()->alias('l')->select("l.*,sum(ll.step_currency) as currency_num,count(ll.id) as people_num")->where([
            'AND',
            ['l.is_delete' => 0],
            ['l.store_id' => $this->store->id]
            ])
        ->leftJoin(['ll' => StepLog::find()
                ->where([
                    'store_id' => $this->store->id,
                    'status' => 2,
                    'type' => 2,
                ]),
            ], 'll.type_id = l.id')
            ->orderBy('open_date desc')->groupBy('l.id');

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->asArray()->all();

        return $this->render('activity', [
            'list' => $list,
            'pagination' => $pagination
        ]);
    }

    public function actionActivityDisband($id)
    {
        $date = date('Y-m-d', time());
        $step = StepActivity::find()->where([
            'AND',
            ['id' => $id],
            ['store_id' => $this->store->id],
            ['is_delete' => 0],
            ['type' => 0],
            (['>=','open_date',$date])
            ])->one();

        if (!$step) {
            return [
                'code' => 0,
                'msg' => '活动不存在',
            ];
        }

        $query = StepLog::find()->select('id,step_id')->where([
                'AND',
                ['store_id' => $this->store->id],
                ['type' => 2],
                ['status' => 2],
                ['type_id' => $step->id],
            ]);
        $count = $query->count();
        $log = $query->asArray()->all();

        if (!$log) {
            $step->type = 2;
            $step->save();
            return [
                'code' => 0,
                'msg' => '操作成功',
            ];
        }

        $ids = array_column($log, 'id');
        $step_ids = array_column($log, 'step_id');
        $currency = StepLog::find()->select('SUM(step_currency) as num')->where([
                'AND',
                ['store_id' => $this->store->id],
                ['type' => 2],
                ['status' => 2],
                ['type_id' => $step->id]
            ])->asArray()->one();

        $t = \Yii::$app->db->beginTransaction();
        $currency_num = floor($currency['num'] / $count * 100) / 100;
        //新增记录
        $array = [];
        foreach ($step_ids as $v) {
            $array[] = [
                $this->store->id, $v, 1, 2, $currency_num, $step->id, time(), time(),
            ];
        }

        StepLog::find()->createCommand()->batchInsert(
            StepLog::tableName(),
            ['store_id', 'step_id', 'status', 'type', 'step_currency', 'type_id', 'raffle_time', 'create_time'],
            $array
        )->execute();

        //增加余额
        $sql = "step_currency + " . $currency_num;
        StepUser::updateAll(
            ['step_currency' => new Expression($sql)],
            ['in', 'id',$step_ids]
        );
        
        $step->type = 2;
        if ($step->save()) {
            $t->commit();
            $ids = StepUser::find()->select('user_id')->where([
                'AND',
                ['store_id' => $this->store->id],
                ['in', 'id', $step_ids]
            ])->column();

            $info = [
                'name' => $step->name,
                'currency_num' => $currency_num
            ];
            $notice = new ActivityMsgTpl($ids[0], 'STEP');
            $notice->sendErrorNotice($ids, $info, 'disband');
            return [
                'code' => 0,
                'msg' => '操作成功',
            ];
        } else {
            $t->rollBack();
        }
    }
 
    //参与名单
    public function actionPartakeList($id)
    {
        $query = StepLog::find()->where([
                'store_id' => $this->store->id,
                'type' => 2,
                'type_id' => $id
            ])->with(['step' => function ($query) {
                $query->with('user');
            }]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('status asc, create_time desc,id desc')->asArray()->all();

        foreach ($list as &$item) {
            $item['addtime'] = date('Y-m-d H:i:s', $item['create_time']);
        };
        unset($item);

        return $this->render('partake-list', [
            'list' => $list,
            'pagination' => $pagination,
        ]);
    }

    //活动编辑
    public function actionActivityEdit($id = null)
    {
        $model = StepActivity::findOne([
            'store_id' => $this->store->id,
            'id' => $id
        ]);
        if (!$model) {
            $model = new StepActivity();
        }
        if (\Yii::$app->request->isPost) {
            $form = new StepActivityForm();
            $form->attributes = \Yii::$app->request->post();
            $form->model = $model;
            $form->store_id = $this->store->id;
            return $form->save();
        };
        return $this->render('activity-edit', [
            'list' => $model,
        ]);
    }

    //活动删除
    public function actionActivityDestroy($id)
    {
        $model = StepActivity::find()->where([
            'AND',
            ['store_id' => $this->store->id],
            ['id' => $id],
            ['not',['type' => 0]]
        ])->one();
        if ($model) {
            $model->is_delete = 1;
            $model->save();
        }
        return [
            'code' => 0,
            'msg' => '操作成功',
        ];
    }

    //活动状态
    public function actionStatusEdit($id = null)
    {
        $post = \Yii::$app->request->post();

        $model = StepActivity::findOne([
            'store_id' => $this->store->id,
            'id' => $post['id']
        ]);
        if ($model) {
            $model->status = $post['status'];
            if ($model->save()) {
                return [
                    'code' => 0,
                    'msg' => '操作成功',
                ];
            } else {
                return (new Model())->getErrorResponse($model);
            }
        }
    }

    //兑换记录
    public function actionLog($id)
    {
        $query = StepLog::find()->where([
                'store_id' => $this->store->id,
                'step_id' => $id,
            ])->with(['order' => function ($query) {
                $query->with('goods');
            }])->with('activity');

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count]);
        $log = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('create_time desc')->asArray()->all();

        return $this->render('log', [
            'log' => $log,
            'pagination' => $pagination,
        ]);
    }




    // 幻灯片列表
    public function actionSlide()
    {
        $form = new BannerForm();
        $form->type = 6;
        $res = $form->getList($this->store->id);
        return $this->render('slide', [
            'list' => $res[0],
            'pagination' => $res[1]
        ]);
    }

    // 幻灯片编辑
    public function actionSlideEdit($id = 0)
    {
        $banner = Banner::findOne(['id' => $id, 'type' => 6]);
        if (!$banner) {
            $banner = new Banner();
        }
        if (\Yii::$app->request->isPost) {
            $form = new BannerForm();
            $model = \Yii::$app->request->post('model');
            $form->attributes = $model;
            $form->store_id = $this->store->id;
            $form->banner = $banner;
            $form->type = 6;
            return $form->save();
        }
        foreach ($banner as $index => $value) {
            $banner[$index] = str_replace("\"", "&quot;", $value);
        }
        return $this->render('slide-edit', [
            'list' => $banner,
        ]);
    }

    // 幻灯片删除
    public function actionSlideDel($id = 0)
    {
        $banner = Banner::findOne(['id' => $id, 'is_delete' => 0, 'type' => 6]);
        if (!$banner) {
            return [
                'code' => 1,
                'msg' => '幻灯片不存在或已经删除',
            ];
        }
        $banner->is_delete = 1;
        if ($banner->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            foreach ($banner->errors as $errors) {
                return [
                    'code' => 1,
                    'msg' => $errors[0],
                ];
            }
        }
    }


    // 流量主列表
    public function actionAd()
    {
        $query = Ad::find()->where([
            'is_delete' => 0,
            'store_id' => $this->store->id
            ]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('create_time desc,id desc')->asArray()->all();

        return $this->render('ad', [
            'list' => $list,
            'pagination' => $pagination
        ]);
    }

     //活动编辑
    public function actionAdEdit($id = null)
    {
        $model = Ad::findOne([
            'store_id' => $this->store->id,
            'id' => $id
        ]);
        if (!$model) {
            $model = new Ad();
        }
        if (\Yii::$app->request->isPost) {
            $form = new AdForm();
            $form->attributes = \Yii::$app->request->post();
            $form->model = $model;
            $form->store_id = $this->store->id;
            return $form->save();
        };
        return $this->render('ad-edit', [
            'list' => $model,
        ]);
    }

    //流量主删除
    public function actionAdDestroy($id)
    {
        $model = Ad::findOne([
            'store_id' => $this->store->id,
            'id' => $id
        ]);
        if ($model) {
            $model->is_delete = 1;
            $model->save();
        }
        return [
            'code' => 0,
            'msg' => '操作成功',
        ];
    }

    //流量主状态
    public function actionAdStatus($id = null)
    {
        $post = \Yii::$app->request->post();
        $model = Ad::findOne([
            'store_id' => $this->store->id,
            'id' => $post['id']
        ]);
        if ($model) {
            $model->status = $post['status'];
            if ($model->save()) {
                return [
                    'code' => 0,
                    'msg' => '操作成功',
                ];
            } else {
                return (new Model())->getErrorResponse($model);
            }
        }
    }
}
