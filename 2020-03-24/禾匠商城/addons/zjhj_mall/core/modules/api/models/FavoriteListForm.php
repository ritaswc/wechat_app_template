<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/30
 * Time: 15:55
 */

namespace app\modules\api\models;

use app\models\Favorite;
use app\models\Goods;
use yii\data\Pagination;
use yii\helpers\VarDumper;

class FavoriteListForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $page;
    public $limit;

    public function rules()
    {
        return [
            [['page', 'limit',], 'integer',],
            [['page'], 'default', 'value' => 1],
            [['limit'], 'default', 'value' => 20],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = Goods::find()->from(Favorite::tableName())
            ->alias('f')->leftJoin(['g' => Goods::tableName()], 'f.goods_id=g.id')
            ->where(['f.user_id' => $this->user_id, 'f.is_delete' => 0, 'g.is_delete' => 0, 'g.status' => 1]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => $this->limit]);
        $list = $query->select('g.id,g.name,g.price,g.is_negotiable')->limit($pagination->limit)->offset($pagination->offset)->orderBy('f.addtime DESC')->all();
        $new_list = [];
        foreach ($list as $i => $goods) {
            if($goods->is_negotiable) {
                $goods->price = Goods::GOODS_NEGOTIABLE;
            }
            $new_list[] = (object)[
                'goods_id' => $goods->id,
                'name' => $goods->name,
                'price' => $goods->price,
                'goods_pic' => $goods->getGoodsPic(0)->pic_url,
                'is_negotiable' => $goods->is_negotiable,
            ];
        }
        return [
            'code' => 0,
            'data' => (object)[
                'row_count' => $count,
                'page_count' => $pagination->pageCount,
                'list' => $new_list,
            ],
        ];
    }
}
