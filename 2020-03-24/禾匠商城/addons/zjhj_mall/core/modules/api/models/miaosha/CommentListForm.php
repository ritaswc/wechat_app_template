<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/17
 * Time: 15:07
 */

namespace app\modules\api\models\miaosha;

use app\models\MiaoshaGoods;
use app\models\MsGoods;
use app\models\MsOrderComment;
use app\models\User;
use app\modules\api\models\ApiModel;
use yii\data\Pagination;

class CommentListForm extends ApiModel
{
    public $goods_id;
    public $score;
    public $page = 1;
    public $limit = 20;

    public $goods;

    public function rules()
    {
        return [
            [['goods_id'], 'required'],
            [['page'], 'integer'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $this->goods = MiaoshaGoods::findOne(['id'=>$this->goods_id]);
        $query = MsOrderComment::find()->alias('oc')
            ->leftJoin(['u' => User::tableName()], 'oc.user_id=u.id')
            ->where(['oc.goods_id' => $this->goods->goods_id, 'oc.is_delete' => 0, 'oc.is_hide' => 0]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('oc.addtime DESC')->asArray()
            ->select('u.nickname,u.avatar_url,oc.score,oc.content,oc.pic_list,oc.addtime')->all();

        foreach ($list as $i => $item) {
            $list[$i]['addtime'] = date('Y-m-d', $item['addtime']);
            $list[$i]['pic_list'] = json_decode($item['pic_list']);
        }
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'row_count' => $count,
                'page_count' => $pagination->pageCount,
                'list' => $list,
                'comment_count' => $this->countData(),
            ],
        ];
    }

    public function countData()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $score_all = MsOrderComment::find()->alias('oc')
            ->where(['oc.goods_id' => $this->goods->goods_id, 'oc.is_delete' => 0, 'oc.is_hide' => 0,])->count();
        $score_3 = MsOrderComment::find()->alias('oc')
            ->where(['oc.goods_id' => $this->goods->goods_id, 'oc.is_delete' => 0, 'oc.is_hide' => 0, 'oc.score' => 3])->count();
        $score_2 = MsOrderComment::find()->alias('oc')
            ->where(['oc.goods_id' => $this->goods->goods_id, 'oc.is_delete' => 0, 'oc.is_hide' => 0, 'oc.score' => 2])->count();
        $score_1 = MsOrderComment::find()->alias('oc')
            ->where(['oc.goods_id' => $this->goods->goods_id, 'oc.is_delete' => 0, 'oc.is_hide' => 0, 'oc.score' => 1])->count();
        return (object)[
            'score_all' => $score_all ? $score_all : 0,
            'score_3' => $score_3 ? $score_3 : 0,
            'score_2' => $score_2 ? $score_2 : 0,
            'score_1' => $score_1 ? $score_1 : 0,
        ];
    }
}
