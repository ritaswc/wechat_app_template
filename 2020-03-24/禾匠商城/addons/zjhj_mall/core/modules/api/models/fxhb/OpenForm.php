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

class OpenForm extends ApiModel
{
    public $user_id;
    public $store_id;

    public function search()
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
        return [
            'code' => 0,
            'data' => [
                'rule' => $setting->rule,
                'coupon_total_money' => $setting->coupon_total_money,
                'hongbao_id' => $hongbao ? $hongbao->id : null,
            ],
        ];
    }
}
