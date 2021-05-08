<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/30
 * Time: 13:22
 */

namespace app\modules\api\models;

use app\models\Favorite;

class FavoriteAddForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $goods_id;

    public function rules()
    {
        return [
            [['goods_id'], 'required',],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $existFavorite = Favorite::findOne([
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'goods_id' => $this->goods_id,
            'is_delete' => 0,
        ]);
        if ($existFavorite) {
            return [
                'code' => 0,
                'msg' => 'success',
            ];
        }
        $favorite = new Favorite();
        $favorite->store_id = $this->store_id;
        $favorite->user_id = $this->user_id;
        $favorite->goods_id = $this->goods_id;
        $favorite->addtime = time();
        if ($favorite->save()) {
            return [
                'code' => 0,
                'msg' => 'success',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => 'fail',
            ];
        }
    }
}
