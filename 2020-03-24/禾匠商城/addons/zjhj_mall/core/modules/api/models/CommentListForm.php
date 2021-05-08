<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/17
 * Time: 15:07
 */

namespace app\modules\api\models;

use app\hejiang\ApiResponse;
use app\models\OrderComment;
use app\models\User;
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

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = OrderComment::find()->alias('oc')->leftJoin(['u' => User::tableName()], 'oc.user_id=u.id')
            ->where(['oc.goods_id' => $this->goods_id, 'oc.is_delete' => 0, 'oc.is_hide' => 0]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('oc.addtime DESC')->asArray()
            ->select('oc.is_virtual,oc.virtual_user,oc.virtual_avatar,u.nickname,u.avatar_url,oc.score,oc.content,oc.pic_list,oc.addtime,oc.reply_content')->all();
        foreach ($list as $i => $item) {
            $list[$i]['addtime'] = date('Y-m-d', $item['addtime']);
            $list[$i]['pic_list'] = json_decode($item['pic_list']);

            $list[$i]['nickname'] = $this->substr_cut($item['nickname']);
            if ($item['is_virtual'] == 1) {
                $list[$i]['nickname'] = $this->substr_cut($item['virtual_user']);
                $list[$i]['avatar_url'] = $item['virtual_avatar'];
            }
            unset($list[$i]['virtual_avatar']);
            unset($list[$i]['is_virtual']);
            unset($list[$i]['virtual_user']);
        };
        $data = [
            'row_count' => $count,
            'page_count' => $pagination->pageCount,
            'list' => $list,
            'comment_count' => $this->countData(),
        ];
        return new ApiResponse(0, 'success', $data);
    }

    public function countData()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $score_all = OrderComment::find()->alias('oc')
            ->where(['oc.goods_id' => $this->goods_id, 'oc.is_delete' => 0, 'oc.is_hide' => 0,])->count();
        $score_3 = OrderComment::find()->alias('oc')
            ->where(['oc.goods_id' => $this->goods_id, 'oc.is_delete' => 0, 'oc.is_hide' => 0, 'oc.score' => 3])->count();
        $score_2 = OrderComment::find()->alias('oc')
            ->where(['oc.goods_id' => $this->goods_id, 'oc.is_delete' => 0, 'oc.is_hide' => 0, 'oc.score' => 2])->count();
        $score_1 = OrderComment::find()->alias('oc')
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
