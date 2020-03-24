<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/1/31
 * Time: 17:46
 */

namespace app\modules\api\models\fxhb;

use app\models\FxhbHongbao;
use app\models\FxhbSetting;
use app\modules\api\models\ApiModel;

class OpenSubmitForm extends ApiModel
{
    public $user_id;
    public $store_id;
    public $form_id;

    public function rules()
    {
        return [
            [['form_id'], 'required'],
        ];
    }

    public function save()
    {
        $setting = FxhbSetting::findOne(['store_id' => $this->store_id]);
        if (!$setting) {
            return [
                'code' => 1,
                'msg' => '活动尚未开启。',
                'game_open' => 0,
            ];
        }
        if ($setting->game_open != 1) {
            return [
                'code' => 1,
                'msg' => '活动已结束。',
                'game_open' => 0,
            ];
        }
        $hongbao = FxhbHongbao::findOne([
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'is_expire' => 0,
            'is_finish' => 0,
            'parent_id' => 0,
        ]);
        if ($hongbao) {
            return [
                'code' => 0,
                'data' => [
                    'hongbao_id' => $hongbao->id,
                ],
            ];
        }
        $hongbao = new FxhbHongbao();
        $hongbao->parent_id = 0;
        $hongbao->store_id = $this->store_id;
        $hongbao->user_id = $this->user_id;
        $hongbao->user_num = $setting->user_num;
        $hongbao->coupon_total_money = $setting->coupon_total_money;
        $hongbao->coupon_money = 0;
        $hongbao->coupon_use_minimum = $setting->coupon_use_minimum;
        $hongbao->coupon_expire = $setting->coupon_expire;
        $hongbao->distribute_type = $setting->distribute_type;
        $hongbao->is_expire = 0;
        $hongbao->expire_time = time() + $setting->game_time * 3600;
        $hongbao->is_finish = 0;
        $hongbao->addtime = time();
        $hongbao->form_id = $this->form_id;
        if ($hongbao->save()) {
            return [
                'code' => 0,
                'data' => [
                    'hongbao_id' => $hongbao->id,
                ],
            ];
        } else {
            return $this->getErrorResponse($hongbao);
        }
    }
}
