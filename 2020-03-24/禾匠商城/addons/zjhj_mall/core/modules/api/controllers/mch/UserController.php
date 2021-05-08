<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/3/6
 * Time: 10:40
 */

namespace app\modules\api\controllers\mch;

use app\hejiang\BaseApiResponse;
use app\models\Mch;
use app\modules\api\models\mch\AccountForm;
use app\modules\api\models\mch\AccountLogForm;
use app\modules\api\models\mch\CashLogForm;
use app\modules\api\models\mch\CashSubmitForm;
use app\modules\api\models\mch\MyshopForm;
use app\modules\api\models\mch\SettingForm;
use app\modules\api\models\mch\SettingSubmitForm;
use app\modules\api\models\mch\TongjiMonthDataForm;
use app\modules\api\models\mch\YearListForm;
use app\utils\GenerateShareQrcode;
use yii\helpers\VarDumper;

/**
 * Class UserController
 * @package app\modules\api\controllers\mch
 * @property Mch $mch
 */
class UserController extends Controller
{
    public function actionMyshop()
    {
        $form = new MyshopForm();
        $form->mch = $this->mch;
        return new BaseApiResponse($form->search());
    }

    public function actionSetting()
    {
        $form = new SettingForm();
        $form->mch = $this->mch;
        return new BaseApiResponse($form->search());
    }

    public function actionSettingSubmit()
    {
        $form = new SettingSubmitForm();
        $form->attributes = \Yii::$app->request->post();
        $form->mch = $this->mch;
        return new BaseApiResponse($form->save());
    }

    public function actionShopQrcode()
    {
        $data = [
            'scene' => 'mch_id:' . $this->mch->id,
            'page' => 'mch/shop/shop',
            'width' => '400',
        ];
        $is_img = false;
        $uid = \Yii::$app->user->id;
        if(\Yii::$app->fromAlipayApp()){
            $scene = "mch_id={$this->mch->id}&uid={$uid}";
        }else{
            $scene = "mch_id:{$this->mch->id},uid:{$uid}";
        };
        $res = GenerateShareQrcode::getQrcode($this->store_id,$scene,240,"mch/shop/shop");
        if($res['code'] == 0){
            $is_img = true;
            $img_path = file_get_contents($res['file_path']);
        } else {
            $img_path = "";
        }
        if ($is_img) {
            $qrcode_pic = md5(json_encode(array_merge($data, [
                    'store_id' => $this->store_id,
                ]))) . '.jpg';
            if (!is_dir(\Yii::$app->basePath . '/web/qrcode')) {
                mkdir(\Yii::$app->basePath . '/web/qrcode');
            }
            $res = file_put_contents(\Yii::$app->basePath . '/web/qrcode/' . $qrcode_pic, $img_path);
            if (!$res) {
                return new BaseApiResponse([
                    'code' => 1,
                    'msg' => '获取小程序码失败，文件写入失败。',
                ]);
            } else {
                return new BaseApiResponse([
                    'code' => 0,
                    'data' => [
                        'header_bg' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/shop/img/qrcode-header-bg.png',
                        'shop_logo' => $this->mch->logo,
                        'shop_name' => $this->mch->name,
                        'qrcode_pic' => str_replace('http://', 'https://', \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/qrcode/' . $qrcode_pic),
                    ],
                ]);
            }
        } else {
            $res = json_decode($this->wechat->curl->response, true);
            return new BaseApiResponse([
                'code' => 1,
                'msg' => $res['errmsg'],
            ]);
        }
    }

    public function actionAccount()
    {
        $form = new AccountForm();
        $form->mch_id = $this->mch->id;
        return new BaseApiResponse($form->search());
    }

    public function actionCash()
    {
        $form = new CashSubmitForm();
        $form->attributes = \Yii::$app->request->post();
        $form->mch_id = $this->mch->id;
        return new BaseApiResponse($form->save());
    }

    public function actionAccountLog()
    {
        $form = new AccountLogForm();
        $form->attributes = \Yii::$app->request->get();
        $form->mch_id = $this->mch->id;
        return new BaseApiResponse($form->search());
    }

    public function actionCashLog()
    {
        $form = new CashLogForm();
        $form->attributes = \Yii::$app->request->get();
        $form->mch_id = $this->mch->id;
        return new BaseApiResponse($form->search());
    }

    public function actionTongjiYearList()
    {
        $form = new YearListForm();
        return new BaseApiResponse($form->search());
    }

    public function actionTongjiMonthData()
    {
        $form = new TongjiMonthDataForm();
        $form->attributes = \Yii::$app->request->get();
        $form->mch_id = $this->mch->id;
        return new BaseApiResponse($form->search());
    }

    public function actionCashPreview()
    {
        $form = new CashSubmitForm();
        $form->mch_id = $this->mch->id;
        $form->store_id = $this->store->id;
        return new BaseApiResponse($form->search());
    }

    public function actionSettleLog()
    {
        $form = new AccountLogForm();
        $form->attributes = \Yii::$app->request->get();
        $form->mch_id = $this->mch->id;
        return new BaseApiResponse($form->settleLog());
    }
}
