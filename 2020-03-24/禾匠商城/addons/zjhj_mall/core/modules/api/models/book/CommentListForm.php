<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/17
 * Time: 15:07
 */

namespace app\modules\api\models\book;

use app\models\User;
use app\models\YyOrderComment;
use app\modules\api\models\ApiModel;
use yii\data\Pagination;

class CommentListForm extends ApiModel
{
    public $goods_id;
    public $score;
    public $page = 1;
    public $limit = 20;

    public function rules()
    {
        return [
            [['goods_id'], 'required'],
            [['page'], 'integer'],
        ];
    }

    /**
     * @return array
     * 评论列表
     */
    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = YyOrderComment::find()
            ->alias('oc')
            ->leftJoin(['u' => User::tableName()], 'oc.user_id=u.id')
            ->where(['oc.goods_id' => $this->goods_id, 'oc.is_delete' => 0, 'oc.is_hide' => 0]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        $list = $query->limit($pagination->limit)
            ->offset($pagination->offset)->orderBy('oc.addtime DESC')
            ->select('u.nickname,u.avatar_url,oc.score,oc.content,oc.pic_list,oc.addtime')
            ->asArray()
            ->all();
        foreach ($list as $i => $item) {
            $list[$i]['addtime'] = date('Y-m-d', $item['addtime']);
            $list[$i]['pic_list'] = json_decode($item['pic_list']);
            $list[$i]['nickname'] = $this->substr_cut($item['nickname']);
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

    /**
     * @return array|object
     * 获取数量
     */
    public function countData()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $score_all = YyOrderComment::find()->alias('oc')
            ->where(['oc.goods_id' => $this->goods_id, 'oc.is_delete' => 0, 'oc.is_hide' => 0,])->count();
        $score_3 = YyOrderComment::find()->alias('oc')
            ->where(['oc.goods_id' => $this->goods_id, 'oc.is_delete' => 0, 'oc.is_hide' => 0, 'oc.score' => 3])->count();
        $score_2 = YyOrderComment::find()->alias('oc')
            ->where(['oc.goods_id' => $this->goods_id, 'oc.is_delete' => 0, 'oc.is_hide' => 0, 'oc.score' => 2])->count();
        $score_1 = YyOrderComment::find()->alias('oc')
            ->where(['oc.goods_id' => $this->goods_id, 'oc.is_delete' => 0, 'oc.is_hide' => 0, 'oc.score' => 1])->count();
        return (object)[
            'score_all' => $score_all ? $score_all : 0,
            'score_3' => $score_3 ? $score_3 : 0,
            'score_2' => $score_2 ? $score_2 : 0,
            'score_1' => $score_1 ? $score_1 : 0,
        ];
    }

    // 将用户名 做隐藏
    private function substr_cut($user_name)
    {
        $strlen = mb_strlen($user_name, 'utf-8');
        $firstStr = mb_substr($user_name, 0, 1, 'utf-8');
        $lastStr = mb_substr($user_name, -1, 1, 'utf-8');
        return $strlen <= 2 ? $firstStr . '*' : $firstStr . str_repeat("*", 2) . $lastStr;
    }
}
