<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/2/24
 * Time: 16:06
 */

namespace app\modules\mch\models\fxhb;

use app\models\FxhbHongbao;
use app\models\User;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;

class FxhbListForm extends MchModel
{
    public $store_id;
    public $page;
    public $limit;
    public $keyword;

    public function rules()
    {
        return [
            [['page', 'limit'], 'integer',],
            [['page',], 'default', 'value' => 1],
            [['limit',], 'default', 'value' => 10],
            ['keyword', 'trim'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = FxhbHongbao::find()
            ->alias('hb')
            ->leftJoin(['u' => User::tableName()], 'hb.user_id=u.id')
            ->where([
                'hb.store_id' => $this->store_id,
                'hb.parent_id' => 0,
            ]);
        if ($this->keyword) {
            $query->andWhere(['LIKE', 'u.nickname', $this->keyword,]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => $this->limit]);
        $list = $query->select('u.nickname,u.platform,u.avatar_url,hb.*')
            ->limit($pagination->limit)->offset($pagination->offset)->orderBy('hb.addtime DESC')
            ->asArray()->all();
        if (!$list) {
            return [
                'code' => 0,
                'data' => [
                    'list' => $list,
                    'count' => $pagination->totalCount,
                    'pagination' => $pagination,
                ],
            ];
        }
        foreach ($list as $i => $item) {
            $sub_list = FxhbHongbao::find()
                ->alias('hb')
                ->leftJoin(['u' => User::tableName()], 'hb.user_id=u.id')
                ->where([
                    'hb.parent_id' => $item['id'],
                ])->select('u.nickname,u.platform,u.avatar_url,hb.*')->asArray()->all();
            $sub_list = $sub_list ? $sub_list : [];
            $list[$i]['sub_list'] = array_merge([$item], $sub_list);
        }
        return [
            'code' => 0,
            'data' => [
                'list' => $list,
                'count' => $pagination->totalCount,
                'pagination' => $pagination,
            ],
        ];
    }
}
