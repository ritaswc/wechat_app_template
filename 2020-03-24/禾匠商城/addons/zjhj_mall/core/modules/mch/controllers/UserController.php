<?php

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/3
 * Time: 13:51
 */

namespace app\modules\mch\controllers;

use app\models\IntegralLog;
use app\models\Level;
use app\models\LevelOrder;
use app\models\Order;
use app\models\Register;
use app\models\Shop;
use app\models\Store;
use app\models\User;
use app\models\UserCard;
use app\models\UserCoupon;
use app\modules\mch\models\ExportList;
use app\modules\mch\models\LevelForm;
use app\modules\mch\models\LevelListForm;
use app\modules\mch\models\recharge\UserRechargeForm;
use app\modules\mch\models\user\integral\IndexRechargeForm;
use app\modules\mch\models\UserCardListForm;
use app\modules\mch\models\UserCouponForm;
use app\modules\mch\models\UserExportList;
use app\modules\mch\models\UserForm;
use app\modules\mch\models\UserListForm;
use yii\data\Pagination;

class UserController extends Controller
{
    public function actionIndex()
    {
        $form = new UserListForm();
        $exportList = $form->excelFields();
        $form->attributes = \Yii::$app->request->get();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        $data = $form->search();
        $level_list = Level::find()->where(['store_id' => $this->store->id, 'is_delete' => 0, 'status' => 1])
            ->orderBy(['level' => SORT_ASC])->asArray()->all();
        return $this->render('index', [
            'row_count' => $data['row_count'],
            'pagination' => $data['pagination'],
            'list' => $data['list'],
            'level_list' => $level_list,
            'exportList' => \Yii::$app->serializer->encode($exportList)
        ]);
    }

    /**
     * @param null $id
     * @param int $status //0--解除核销员  1--设置核销员
     * @param int $edit //0--设置/取消核销员 1--变更门店
     * @return null
     * 设置/取消核销员
     */
    public function actionClerkEdit($id = null, $status = 0, $shop_id = 0, $edit = 0)
    {
        $user = User::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$user) {
            return [
                'code' => 1,
                'msg' => '网络异常',
            ];
        }
        if ($status == 1) {
            $shop_exit = Shop::find()->where(['store_id' => $this->store->id, 'is_delete' => 0, 'id' => $shop_id])->exists();
            if (!$shop_exit) {
                return [
                    'code' => 1,
                    'msg' => '店铺不存在',
                ];
            }
            $user->shop_id = $shop_id;
        }
        if ($edit == 0) {
            if ($user->is_clerk == $status) {
                return [
                    'code' => 1,
                    'msg' => '网络异常',
                ];
            }
            $user->is_clerk = $status;
            if ($status == 0) {
                $user->shop_id = 0;
            }
        }
        if ($user->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '网络异常',
            ];
        }
    }

    /**
     * @return string
     * 核销员列表
     */
    public function actionClerk()
    {
        // 数据库操作太多注释掉,2018-05-28,luwei
        // User::updateAll(['shop_id' => 0], ['is_clerk' => 0]);
        $form = new UserListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->is_clerk = 1;
        $data = $form->search();
        $data_list = $form->getUser();

        $clerk = array();
        foreach ($data['list'] as $k => $v) {
            $clerk[] = $v['id'];
        };
        $detail = Order::find()->where(['in', 'clerk_id', $clerk])->andwhere(['is_pay' => 1, 'store_id' => $this->store->id])->asArray()->all();

        foreach ($data['list'] as $k => $v) {
            $pay_price = 0;
            foreach ($detail as $k1 => $v1) {
                if ($v1['clerk_id'] == $v['id']) {
                    $pay_price += $v1['pay_price'];
                }
            }
            $data['list'][$k]['total_price'] = $pay_price;
        }

        $shop_list = Shop::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])->asArray()->all();
        return $this->render('clerk', [
            'row_count' => $data['row_count'],
            'pagination' => $data['pagination'],
            'list' => $data['list'],
            'user_list' => \Yii::$app->serializer->encode($data_list),
            'shop_list' => \Yii::$app->serializer->encode($shop_list),
        ]);
    }

    public function actionGetUser()
    {
        $form = new UserListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $data_list = $form->getUser();
        return \Yii::$app->serializer->encode($data_list);
    }

    public function actionDel($id = null)
    {
        $user = User::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$user) {
            return [
                'code' => 1,
                'msg' => '用户不存在',
            ];
        }
        $user->is_delete = 1;
        if ($user->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '网络异常',
            ];
        }
    }

    public function actionCoupon()
    {
        $form = new UserCouponForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $form->limit = 10;
        $arr = $form->search();
        $data = $form->getCountData();
        $user_id = \Yii::$app->request->get('user_id');
        $user = User::findOne(['store_id' => $this->store->id, 'id' => $user_id]);
        return $this->render('coupon', [
            'row_count' => $arr['row_count'],
            'pagination' => $arr['pagination'],
            'list' => $arr['list'],
            'data' => $data,
            'user' => $user,
        ]);
    }

    public function actionCouponDel($id = null)
    {
        $user_coupon = UserCoupon::findOne(['id' => $id, 'store_id' => $this->store->id]);
        if (!$user_coupon) {
            return [
                'code' => 1,
                'msg' => '网络异常_1',
            ];
        }

        $user_coupon->is_delete = 1;
        if ($user_coupon->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '网络异常',
            ];
        }
    }

    /**
     * 会员等级
     */
    public function actionLevel()
    {
        $form = new LevelListForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $arr = $form->search();
        return $this->render('level', [
            'list' => $arr['list'],
            'pagination' => $arr['p'],
            'row_count' => $arr['row_count'],
        ]);
    }

    /**
     * 会员等级编辑
     */
    public function actionLevelEdit($id = null)
    {
        $level = Level::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$level) {
            $level = new Level();
        }
        $store = Store::findOne(['id' => $this->store->id]);
        if (\Yii::$app->request->isAjax) {
            $form = new LevelForm();
            $post = \Yii::$app->request->post();
            $form->scenario = $post['scene'];
            $form->store_id = $this->store->id;
            $form->model = $level;
            $form->attributes = $post;
            if ($post['scene'] == 'edit') {
                return $form->save();
            } elseif ($post['scene'] == 'content') {
                return $form->saveContent();
            }
        }
        foreach ($level as $index => $value) {
            if ($index == 'synopsis') {
                $level['synopsis'] = json_decode($value, true);
            } else {
                $level[$index] = str_replace("\"", "&quot;", $value);
            }

        };
        return $this->render('level-edit', [
            'level' => $level,
            'store' => $store,
        ]);
    }

    /**
     * 会员等级启用/禁用
     */
    public function actionLevelType($type = 0, $id = null)
    {
        $level = Level::find()->where(['id' => $id, 'store_id' => $this->store->id])->one();
        if (!$level) {
            return [
                'code' => 1,
                'msg' => '会员等级不存在',
            ];
        }
        $level->status = $type;
        if ($type == 0) {
            $exit = User::find()->where(['store_id' => $this->store->id, 'level' => $level->level])->exists();
            if ($exit) {
                return [
                    'code' => 1,
                    'msg' => '该会员等级下有会员，不可禁用',
                ];
            }
        }
        if ($level->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '网络异常',
            ];
        }
    }

    /**
     * 会员等级删除
     */
    public function actionLevelDel($id = null)
    {
        $level = Level::findOne(['id' => $id, 'store_id' => $this->store->id]);
        if (!$level) {
            return [
                'code' => 1,
                'msg' => '会员等级不存在',
            ];
        }
        $exit = User::find()->where(['store_id' => $this->store->id, 'level' => $level->level])->exists();
        if ($exit) {
            return [
                'code' => 1,
                'msg' => '该会员等级下有会员，不可删除',
            ];
        }
        $level->is_delete = 1;
        if ($level->save()) {
            $level->delete();
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '网络异常',
            ];
        }
    }

    /**
     * 会员编辑
     */
    public function actionEdit($id = null)
    {
        $user = User::findOne(['id' => $id, 'store_id' => $this->store->id]);
        if (!$user) {
            $this->redirect(\Yii::$app->urlManager->createUrl(['mch/user/index']))->send();
        }
        if (\Yii::$app->request->isAjax) {
            $form = new UserForm();
            $form->store_id = $this->store->id;
            $form->user = $user;
            $form->attributes = \Yii::$app->request->post();
            return $form->save();
        }
        $level = Level::findAll(['store_id' => $this->store->id, 'status' => 1, 'is_delete' => 0]);
        foreach ($user as $index => $value) {
            $user[$index] = str_replace("\"", "&quot;", $value);
        }

        return $this->render('edit', [
            'user' => $user,
            'level' => $level,
        ]);
    }

    /**
     * @return mixed|string
     * 后台用户积分充值
     */
    public function actionRechange()
    {
        $integral = (int)\Yii::$app->request->post('integral');
        $user_id = \Yii::$app->request->post('user_id');
        $rechangeType = \Yii::$app->request->post('rechangeType');
        $user = User::findOne(['id' => $user_id, 'store_id' => $this->store->id]);

        $integralLog = new IntegralLog();
        $integralLog->user_id = $user->id;
        $register = new Register();
        $register->store_id = $this->store->id;
        $register->user_id = $user->id;
        $register->register_time = '..';
        $register->addtime = time();
        $register->continuation = 0;
        $register->type = 3;
        if ($rechangeType == '2') {
            $register->integral = '-' . $integral;
        } elseif ($rechangeType == '1') {
            $register->integral = $integral;
        }
        $register->save();
        if (!$user) {
            return [
                'code' => 1,
                'msg' => '用户不存在，或已删除',
            ];
        }
        if (empty($integral)) {
            return [
                'code' => 1,
                'msg' => '积分设置不正确',
            ];
        }
        if ($this->is_we7) {
            $admin = \Yii::$app->user->identity;
        } elseif ($this->is_ind) {
            $admin = \Yii::$app->admin->identity;
        } else {
            $admin = \Yii::$app->mchRoleAdmin->identity;
        }
        if ($rechangeType == '2') {
            if ($integral > $user->integral) {
                return [
                    'code' => 1,
                    'msg' => '用户当前积分不足',
                ];
            }
            $user->integral -= $integral;
        } elseif ($rechangeType == '1') {
            $user->integral += $integral;
            $user->total_integral += $integral;
        }
        if (!$user->save()) {
            return [
                'code' => 1,
                'msg' => '操作失败！请重试',
            ];
        }
        if ($rechangeType == '2') {
            $integralLog->content = "管理员： " . $admin->username . " 后台操作账号：" . $user->nickname . " 积分扣除：" . $integral . " 积分";
        } elseif ($rechangeType == '1') {
            $integralLog->content = "管理员： " . $admin->username . " 后台操作账号：" . $user->nickname . " 积分充值：" . $integral . " 积分";
        }
        $integralLog->integral = $integral;
        $integralLog->addtime = time();
        $integralLog->username = trim($user->nickname) ? $user->nickname : '未知';
        $integralLog->operator = $admin->username ? $admin->username : '未知';
        $integralLog->store_id = $this->store->id;
        $integralLog->operator_id = $admin->id;

        if ($integralLog->save()) {
            return [
                'code' => 0,
                'msg' => '操作成功',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '操作失败',
            ];
        }
    }

    /**
     * 个人积分充值列表
     * @param int $user_id
     * @return string
     */
    public function actionRechangeLog($user_id = 0)
    {
        $model = new IndexRechargeForm();
        $model->userId = $user_id;
        $model->type = IntegralLog::TYPE_INTEGRAL;
        $res = $model->getIntegralRechargeList();

        return $this->render('rechange-log', [
            'list' => $res['list'],
            'pagination' => $res['pagination'],
        ]);
    }

    /**
     * 积分充值列表
     * @return string
     */
    public function actionIntegralRechangeList()
    {
        $userExport = new UserExportList();
        $exportList = $userExport->getList(3);

        $model = new IndexRechargeForm();
        $model->attributes = \Yii::$app->request->get();
        $res = $model->getIntegralRechargeList();

        return $this->render('integral-rechange-list', [
            'list' => $res['list'],
            'pagination' => $res['pagination'],
            'exportList' => \Yii::$app->serializer->encode($exportList)
        ]);
    }

    /**
     * 会员卡券
     */
    public function actionCard()
    {
        $form = new UserCardListForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $user_id = \Yii::$app->request->get('user_id');
        $user = User::findOne(['store_id' => $this->store->id, 'id' => $user_id]);
        $clerk_id = \Yii::$app->request->get('clerk_id');
        $clerk = User::findOne(['store_id' => $this->store->id, 'id' => $clerk_id]);
        $shop_id = \Yii::$app->request->get('shop_id');
        $shop = Shop::findOne(['store_id' => $this->store->id, 'id' => $shop_id]);
        $arr = $form->search();
        $data = $form->getCount();
        return $this->render('card', [
            'list' => $arr['list'],
            'pagination' => $arr['pagination'],
            'row_count' => $arr['row_count'],
            'data' => $data,
            'user' => $user,
            'clerk' => $clerk,
            'shop' => $shop,
        ]);
    }

    /**
     * 会员充值记录
     */
    public function actionRecharge($user_id = null)
    {
        $userExport = new UserExportList();
        $exportList = $userExport->getList(1);

        $form = new UserRechargeForm();
        $form->store_id = $this->store->id;
        $form->user_id = $user_id;
        $form->attributes = \Yii::$app->request->get();
        $form->attributes = \Yii::$app->request->post();

        $arr = $form->search();
        return $this->render('recharge', [
            'list' => $arr['list'],
            'exportList' => \Yii::$app->serializer->encode($exportList),
            'pagination' => $arr['pagination'],
            'row_count' => $arr['row_count'],
        ]);
    }

    /**
     * 会员购买记录
     */
    public function actionBuy($keyword = null)
    {
        $dateStart = \Yii::$app->request->get('date_start');
        $dateEnd = \Yii::$app->request->get('date_end');

        $userExport = new UserExportList();
        $exportList = $userExport->getList(2);

        $query = LevelOrder::find()->alias('ro')->where(['ro.store_id' => $this->store->id, 'ro.is_delete' => 0, 'ro.is_pay' => 1])
            ->leftJoin(['u' => User::tableName()], 'u.id=ro.user_id');

        if ($keyword) {
            $query->andWhere(['like', 'u.nickname', $keyword]);
        }
        if ($dateStart) {
            $query->andWhere(['>', 'ro.addtime', strtotime($dateStart)]);
        }
        if ($dateEnd) {
            $query->andWhere(['<', 'ro.addtime', strtotime($dateEnd + 1)]);
        }
        $currentQuery = Level::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])
            ->andWhere('level=ro.current_level')->select('name');
        $afterQuery = Level::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])
            ->andWhere('level=ro.after_level')->select('name');
        $query->select(['ro.*', 'u.nickname', 'u.platform', 'after_name' => $afterQuery, 'current_name' => $currentQuery]);

        // excel导出
        $flag = \Yii::$app->request->post('flag');
        if ($flag == "EXPORT") {
            $userExport = new UserExportList();
            $userExport->fields = \Yii::$app->request->post('fields');
            $userExport->memberBuyForm($query);
        }
        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => 20]);
        $list = $query->limit($p->limit)->offset($p->offset)->orderBy('ro.addtime DESC')->asArray()->all();

        foreach ($list as $k => $v) {
            if (!$v['current_name']) {
                $list[$k]['current_name'] = '普通会员';
            }
        }

        return $this->render('buy', [
            'list' => $list,
            'pagination' => $p,
            'row_count' => $count,
            'exportList' => \Yii::$app->serializer->encode($exportList),
        ]);
    }

    // 充值金额
    public function actionRechargeMoney()
    {
        if (\Yii::$app->request->isPost) {
            $form = new \app\modules\mch\models\UserRechargeForm();
            $form->attributes = \Yii::$app->request->post('data');
            $form->store_id = $this->store->id;
            if (!\Yii::$app->user->isGuest) {
                $form->admin = \Yii::$app->user->identity;
            } elseif (!\Yii::$app->admin->isGuest) {
                $form->admin = \Yii::$app->admin->identity;
            } elseif (!\Yii::$app->mchRoleAdmin->isGuest) {
                $form->admin = \Yii::$app->mchRoleAdmin->identity;
            } else {
                return [
                    'code' => 1,
                    'msg' => '登录账号有误'
                ];
            }
            return $form->save();
        } else {
            return [
                'code' => 1,
                'msg' => '网络异常，请刷新重试'
            ];
        }
    }

    /**
     * 个人余额充值记录
     * @param null $user_id
     * @return string
     */
    public function actionRechargeMoneyLog($user_id)
    {
        $model = new IndexRechargeForm();
        $model->userId = $user_id;
        $model->type = IntegralLog::TYPE_BALANCE;
        $res = $model->getIntegralRechargeList();

        return $this->render('rechange-log', [
            'list' => $res['list'],
            'pagination' => $res['pagination'],
            'type' => 'money'
        ]);
    }

    // 查找所有分销商
    public function actionGetShare()
    {
        $model = new UserListForm();
        $model->attributes = \Yii::$app->request->get();
        $model->store_id = $this->store->id;
        return $model->getShare();
    }

    // 获取核销员
    public function actionGetClerk()
    {
        $form = new UserListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $data_list = $form->getClerk();
        return \Yii::$app->serializer->encode($data_list);
    }

    // 删除用户卡券
    public function actionCardDelete()
    {
        if (\Yii::$app->request->isAjax) {
            $id = \Yii::$app->request->get('id');
            if ($id == null) {
                return [
                    'code' => 1,
                    'msg ' => '请选择需要删除的卡券'
                ];
            }
            if (is_array($id)) {
                return UserCard::deleteItemAll($id);
            } else {
                return UserCard::deleteItem($id);
            }
        }
    }
}
