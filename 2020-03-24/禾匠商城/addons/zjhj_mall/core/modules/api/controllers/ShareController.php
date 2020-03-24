<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8
 * Time: 14:15
 */

namespace app\modules\api\controllers;

use app\utils\CreateQrcode;
use app\hejiang\ApiResponse;
use app\hejiang\BaseApiResponse;
use app\models\Cash;
use app\models\Color;
use app\models\Option;
use app\models\Qrcode;
use app\models\Setting;
use app\models\Share;
use app\models\Store;
use app\models\UploadConfig;
use app\models\UploadForm;
use app\models\User;
use app\modules\api\behaviors\LoginBehavior;
use app\modules\api\models\BindForm;
use app\modules\api\models\CashForm;
use app\modules\api\models\CashListForm;
use app\modules\api\models\QrcodeForm;
use app\modules\api\models\ShareForm;
use app\modules\api\models\ShareQrcodeForm;
use app\modules\api\models\TeamForm;
use app\modules\mch\models\ShareCustomForm;
use yii\helpers\VarDumper;

class ShareController extends Controller
{

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginBehavior::className(),
            ],
        ]);
    }

    /**
     * @return mixed|string
     * 申请成为分销商
     */
    public function actionJoin()
    {
        $share = Share::findOne(['user_id' => \Yii::$app->user->identity->id, 'store_id' => $this->store->id, 'is_delete' => 0]);
        if (!$share) {
            $share = new Share();
        }
        $share_setting = Setting::findOne(['store_id' => $this->store_id]);
        $form = new ShareForm();
        $form->share = $share;
        $form->store_id = $this->store_id;
        $form->attributes = \Yii::$app->request->post();
        if ($share_setting->share_condition == 1) {
            $form->scenario = "APPLY";
        } elseif ($share_setting->share_condition == 0 || $share_setting->share_condition == 2) {
            $form->agree = 1;
        }
        return new BaseApiResponse($form->save());
    }

    /**
     * @return mixed|string
     * 获取用户的审核状态
     */
    public function actionCheck()
    {
        return new BaseApiResponse([
            'code' => 0,
            'msg' => 'success',
            'data' => \Yii::$app->user->identity->is_distributor,
            'level' => \Yii::$app->user->identity->level
        ]);
        $setting = Setting::findOne(['store_id' => $this->store_id]);
        if ($setting->share_condition == 0) {
            $share = Share::findOne(['user_id' => \Yii::$app->user->identity->id, 'store_id' => $this->store->id, 'is_delete' => 0]);
            if (!$share) {
                $share = new Share();
            }
            $form = new ShareForm();
            $form->share = $share;
            $form->store_id = $this->store_id;
            $form->agree = 1;
//            $form->scenario = "NONE_CONDITION";
            $form->attributes = \Yii::$app->request->post();
            $res = $form->save();
            if ($res['code'] == 0) {
                return json_encode([
                    'code' => 0,
                    'msg' => 'success',
                    'data' => 2,
                    'level' => \Yii::$app->user->identity->level
                ], JSON_UNESCAPED_UNICODE);
            }
        } else {
            return json_encode([
                'code' => 0,
                'msg' => 'success',
                'data' => \Yii::$app->user->identity->is_distributor,
                'level' => \Yii::$app->user->identity->level
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @return mixed|string
     * 获取分销中心数据
     */
    public function actionGetInfo_1()
    {
        $res = [
            'code' => 0,
            'msg' => 'success',
        ];
        //获取分销佣金及提现
        $form = new ShareForm();
        $form->store_id = $this->store_id;
        $form->user_id = \Yii::$app->user->identity->id;
        $res['data']['price'] = $form->getPrice();
        //获取我的团队
        $team = new TeamForm();
        $team->user_id = \Yii::$app->user->id;
        $team->store_id = $this->store_id;
        $team->status = -1;
        $get_team = $team->getList();
        $res['data']['team_count'] = $get_team['data']['first'] + $get_team['data']['second'] + $get_team['data']['third'];
        //获取分销订单总额
        $team->limit = -1;
        $order = $team->GetOrder();
        $res['data']['order_money'] = doubleval(sprintf('%.2f', $order));
        return new ApiResponse(0, 'success', $res['data']);
    }

    /**
     * @return mixed|string
     * 获取分销中心数据
     */
    public function actionGetInfo()
    {
        if (\Yii::$app->user->identity->is_distributor == 0) {
            $data = [
                'is_distributor'=>\Yii::$app->user->identity->is_distributor
            ];
            return new ApiResponse(1, 'success', $data);
        }
        $res = [
            'code' => 0,
            'msg' => 'success',
        ];
        //获取分销佣金及提现
        $form = new ShareForm();
        $form->store_id = $this->store_id;
        $form->user_id = \Yii::$app->user->identity->id;
        $res['data']['price'] = $form->getPrice();

        //获取我的团队
        $team = new TeamForm();
        $team->user_id = \Yii::$app->user->identity->id;
        $team->store_id = $this->store_id;
        $arr = $team->getOrderCount();
        $res['data']['team_count'] = $arr['team_count'];
        $res['data']['order_money'] = $arr['order_money'];
        $res['data']['order_money_un'] = $arr['order_money_un'];

        //获取分销自定义数据
        $custom_form = new ShareCustomForm();
        $custom_form->store_id = $this->store->id;
        $custom = $custom_form->getData();
        $res['data']['custom'] = $custom['data'];

        return new ApiResponse(0, 'success', $res['data']);
    }

    /**
     * @return mixed|string
     * 获取佣金相关
     */
    public function actionGetPrice()
    {
        $form = new ShareForm();
        $form->store_id = $this->store_id;
        $form->user_id = \Yii::$app->user->identity->id;

        $res['data']['price'] = $form->getPrice();
        $setting = Setting::findOne(['store_id' => $this->store->id]);
        $res['data']['pay_type'] = $setting->pay_type;
        $res['data']['bank'] = $setting->bank;
        $res['data']['remaining_sum'] = $setting->remaining_sum;

        $cash_last = Cash::find()->where(['store_id' => $this->store->id, 'user_id' => \Yii::$app->user->identity->id, 'is_delete' => 0])
            ->orderBy(['id' => SORT_DESC])->select(['name', 'mobile', 'type', 'bank_name'])->asArray()->one();

        $res['data']['cash_last'] = $cash_last;
        $cash_max_day = floatval(Option::get('cash_max_day', $this->store_id, 'share', 0));
        if ($cash_max_day) {
            $cash_sum = Cash::find()->where([
                'store_id' => $this->store->id,
                'is_delete' => 0,
                'status' => [0, 1, 2, 5],
            ])->andWhere([
                'AND',
                ['>=', 'addtime', strtotime(date('Y-m-d 00:00:00'))],
                ['<=', 'addtime', strtotime(date('Y-m-d 23:59:59'))],
            ])->sum('price');
            $cash_max_day = $cash_max_day - ($cash_sum ? $cash_sum : 0);
            $res['data']['cash_max_day'] = max(0, floatval(sprintf('%.2f', $cash_max_day)));
        } else {
            $res['data']['cash_max_day'] = -1;
        }
        $cashServiceCharge = floatval(Option::get('cash_service_charge', $this->store_id, 'share', 0));
        $res['data']['cash_service_charge'] = $cashServiceCharge;
        if($cashServiceCharge == 0){
            $res['data']['service_content'] = "";
        }else{
            $res['data']['service_content'] = "提现需要加收{$cashServiceCharge}%手续费";
        }

        $wxappUrl = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/wxapp/images';
        $payTypeList = [
            [
                'icon' => $wxappUrl . '/icon-share-wechat.png',
                'name' => '微信',
                'is_show' => false
            ],
            [
                'icon' => $wxappUrl . '/icon-share-ant.png',
                'name' => '支付宝',
                'is_show' => false
            ],
            [
                'icon' => $wxappUrl . '/icon-share-bank.png',
                'name' => '银行卡',
                'is_show' => false
            ],
            [
                'icon' => $wxappUrl . '/gold.png',
                'name' => '余额',
                'is_show' => false
            ],
        ];

        switch($res['data']['pay_type']){
            case 0:
                $payTypeList[0]['is_show'] = true;
                break;
            case 1:
                $payTypeList[1]['is_show'] = true;
                break;
            case 2:
                $payTypeList[0]['is_show'] = true;
                $payTypeList[1]['is_show'] = true;
                break;
            default:
                break;
        }
        if($res['data']['bank'] && $res['data']['bank'] == 1){
            $payTypeList[2]['is_show'] = true;
        }

        if($res['data']['remaining_sum'] && $res['data']['remaining_sum'] == 1){
            $payTypeList[3]['is_show'] = true;
        }
        $res['data']['pay_type_list'] = $payTypeList;

        return new ApiResponse(0, 'success', $res['data']);
    }

    /**
     * @return mixed|string
     * 申请提现
     */
    public function actionApply()
    {
        $form = new CashForm();
        $form->user_id = \Yii::$app->user->identity->id;
        $form->store_id = $this->store_id;
        $form->attributes = \Yii::$app->request->post();
        return new BaseApiResponse($form->save());
    }

    /**
     * 提现明细列表
     */
    public function actionCashDetail()
    {
        $form = new CashListForm();
        $get = \Yii::$app->request->get();
        $form->attributes = $get;
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return $form->getList();
    }

    /**
     * @return mixed|string
     * 获取推广海报
     */
    public function actionGetQrcode()
    {
        $form = new ShareQrcodeForm();
        $form->store_id = $this->store->id;
        $form->type = 4;
        $form->user = \Yii::$app->user->identity;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->search());
    }

    /**
     * @return mixed|string
     * 商店分销设置信息
     */
    public function actionShopShare()
    {
        $list = Setting::find()->alias('s')
            ->where(['s.store_id' => $this->store_id])
            ->leftJoin('{{%qrcode}} q', 'q.store_id=s.store_id and q.is_delete=0')
            ->select(['s.level', 'q.qrcode_bg'])
            ->asArray()->one();
        return new ApiResponse(0, 'success', $list);
    }

    /**
     * @return mixed|string
     * 绑定上下级关系
     */
    public function actionBindParent()
    {
        $form = new BindForm();
        $form->user_id = \Yii::$app->user->id;
        $form->store_id = $this->store_id;
        $form->parent_id = \Yii::$app->request->get('parent_id');
        $form->condition = intval(\Yii::$app->request->get('condition'));

        return new BaseApiResponse($form->save());
    }

    /**
     * @return mixed|string
     * 获取团队详情
     */
    public function actionGetTeam()
    {
        $form = new TeamForm();
        $form->attributes = \Yii::$app->request->get();
        $form->user_id = \Yii::$app->user->id;
        $form->store_id = $this->store_id;
        $form->scenario = "TEAM";
        return new BaseApiResponse($form->getTeam());
    }

    /**
     * @return mixed|string
     * 获取分销订单
     */
    public function actionGetOrder()
    {
        $form = new TeamForm();
        $form->attributes = \Yii::$app->request->get();
        $form->user_id = \Yii::$app->user->id;
        $form->store_id = $this->store_id;
        $form->scenario = "ORDER";
        return new BaseApiResponse($form->getOrder());
    }

    public function actionIndex()
    {
        $share_setting = Setting::find()->where(['store_id' => $this->store->id])->asArray()->one();
        return new ApiResponse(0, 'success', $share_setting);
    }
}
