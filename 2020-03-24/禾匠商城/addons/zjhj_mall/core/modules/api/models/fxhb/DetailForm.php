<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/2/3
 * Time: 16:58
 */

namespace app\modules\api\models\fxhb;

use app\models\FxhbHongbao;
use app\models\FxhbSetting;
use app\models\User;
use app\modules\api\models\GoodsListForm;
use app\modules\api\models\ApiModel;

class DetailForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $id;

    public function rules()
    {
        return [
            [['id'], 'required'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
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
        $hongbao = FxhbHongbao::find()
            ->alias('fh')
            ->select('fh.*,u.nickname,u.avatar_url')
            ->leftJoin(['u' => User::tableName()], 'fh.user_id=u.id')
            ->where([
                'fh.store_id' => $this->store_id,
                'fh.id' => $this->id,
                'fh.parent_id' => 0,
            ])->asArray()->one();

        if (!$hongbao) {
            return [
                'code' => 1,
                'msg' => '红包不存在',
                'game_open' => 1,
            ];
        }

        //时间过期
        if ($hongbao['is_expire'] == 0 && $hongbao['is_finish'] == 0 && time() > $hongbao['expire_time']) {
            $hongbao['is_expire'] = 1;
            FxhbHongbao::updateAll(['is_expire' => 1], ['id' => $hongbao['id']]);
        }

        $hongbao_list = FxhbHongbao::find()
            ->alias('fh')
            ->select('fh.*,u.nickname,u.avatar_url')
            ->leftJoin(['u' => User::tableName()], 'fh.user_id=u.id')
            ->where([
                'fh.parent_id' => $hongbao['id'],
            ])->asArray()->all();
        if (!is_array($hongbao_list)) {
            $hongbao_list = [];
        }
        $hongbao_list = array_merge([$hongbao], $hongbao_list);

        /** @var integer $rest_user_num 剩余参与人数 */
        $rest_user_num = intval($hongbao['user_num']) - count($hongbao_list);

        /** @var integer $rest_time 剩余参与时间（秒） */
        $rest_time = intval($hongbao['expire_time'] - time());

        for ($i = count($hongbao_list); $i < intval($hongbao['user_num']); $i++) {
            $hongbao_list[] = false;
        }
        $this->setBest($hongbao_list);

        if ($hongbao['is_finish'] == 1) {
            $my_coupon = $this->getMyCoupon($hongbao_list);
        } else {
            $my_coupon = false;
        }
        $share_title = $setting->share_title;
        $share_title = str_replace("\r\n", "\n", $share_title);
        $share_titles = explode("\n", $share_title);
        $share_title = $share_titles[mt_rand(0, (count($share_titles) - 1))];
        return [
            'code' => 0,
            'data' => [
                'rule' => $setting->rule,
                'share_pic' => $setting->share_pic,
                'share_title' => $share_title,
                //'coupon_total_money' => trim($hongbao['coupon_total_money'] . '', '.00'),
                'coupon_total_money' => $hongbao['coupon_total_money'],
                'rest_user_num' => $rest_user_num,
                'rest_time' => $rest_time,
                'hongbao_list' => $hongbao_list,
                'hongbao' => $hongbao,
                'my_coupon' => $my_coupon,
                'is_my_hongbao' => $hongbao['user_id'] == $this->user_id,
                'goods_list' => $this->getGoodsList(),
            ],
        ];
    }

    private function setBest(&$hongbao_list)
    {
        $max = 0;
        foreach ($hongbao_list as $i => $item) {
            if (!$item) {
                continue;
            }
            $max = max($item['coupon_money'], $max);
        }
        foreach ($hongbao_list as $i => $item) {
            if (!$item) {
                continue;
            }
            if ($item['coupon_money'] == $max) {
                $hongbao_list[$i]['is_best'] = true;
            } else {
                $hongbao_list[$i]['is_best'] = false;
            }
        }
    }

    private function getMyCoupon($hongbao_list)
    {
        foreach ($hongbao_list as $item) {
            if ($item == false) {
                continue;
            }
            if ($item['user_id'] == $this->user_id) {
                return [
                    'money' => number_format($item['coupon_money'], 2, '.', ''),
                    'use_minimum' => number_format($item['coupon_use_minimum'], 2, '.', '') . '元',
                ];
            }
        }
        return false;
    }

    private function getGoodsList()
    {
        $model = new GoodsListForm();
        $model->store_id = $this->store_id;
        $model->limit = 20;
        $res = $model->search();
        if (!$res->data || !$res->data['list'])
            return null;
        $list = $res->data['list'];
        if (count($list) % 2 != 0 && count($list) >= 1) {
            unset($res[count($list) - 1]);
        }
        return count($list) ? $list : null;
    }
}
