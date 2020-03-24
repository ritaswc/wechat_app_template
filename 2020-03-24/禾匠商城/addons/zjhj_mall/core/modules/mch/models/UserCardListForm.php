<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/29
 * Time: 14:04
 */

namespace app\modules\mch\models;

use app\models\Shop;
use app\models\User;
use app\models\UserCard;
use yii\data\Pagination;

class UserCardListForm extends MchModel
{
    public $store_id;
    public $user_id;

    public $page;
    public $limit;
    public $status;
    public $clerk_id;
    public $shop_id;
    public $keyword;
    public $add_time_begin;
    public $add_time_end;
    public $clerk_time_begin;
    public $clerk_time_end;
    public $shop_name;
    public $card_name;

    public function rules()
    {
        return [
            [['page'], 'default', 'value' => 1],
            [['limit'], 'default', 'value' => 20],
            [['status'], 'default', 'value' => -1],
            [['user_id', 'shop_id', 'clerk_id'], 'integer'],
            [['keyword', 'add_time_begin', 'add_time_end', 'clerk_time_begin', 'clerk_time_end','shop_name','card_name'], 'trim']
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $query = UserCard::find()->alias('uc')->where(['uc.store_id' => $this->store_id, 'uc.is_delete' => 0])
            ->leftJoin(User::tableName() . ' u', 'u.id=uc.user_id')->leftJoin(Shop::tableName().' s', 's.id=uc.shop_id');

        if ($this->status == 0) {
            $query->andWhere(['uc.is_use' => 0]);
        }
        if ($this->status == 1) {
            $query->andWhere(['uc.is_use' => 1]);
        }
        if ($this->user_id) {
            $query->andWhere(['uc.user_id' => $this->user_id]);
        }
        if ($this->clerk_id) {
            $query->andWhere(['uc.clerk_id' => $this->clerk_id]);
        }
        if ($this->shop_id) {
            $query->andWhere(['uc.shop_id' => $this->shop_id]);
        }
        if ($this->keyword) {
            $query->andWhere(['like','u.nickname',$this->keyword]);
        }
        if ($this->add_time_begin) {
            $query->andWhere(['>=','uc.addtime',strtotime($this->add_time_begin)]);
        }
        if ($this->add_time_end) {
            $query->andWhere(['<=','uc.addtime',strtotime($this->add_time_end)]);
        }
        if ($this->clerk_time_begin) {
            $query->andWhere(['>=','uc.clerk_time',strtotime($this->clerk_time_begin)]);
        }
        if ($this->clerk_time_end) {
            $query->andWhere(['<=','uc.clerk_time',strtotime($this->clerk_time_end)]);
        }
        if ($this->shop_name) {
            $query->andWhere(['like','s.name',$this->shop_name]);
        }
        if ($this->card_name) {
            $query->andWhere(['like','uc.card_name',$this->card_name]);
        }
        $count = $query->count();

        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);

        $list = $query->select([
            'uc.*','u.nickname','s.name shop_name'
        ])->limit($p->limit)->offset($p->offset)->orderBy(['addtime' => SORT_DESC])->asArray()->all();
        return [
            'list' => $list,
            'pagination' => $p,
            'row_count' => $count
        ];
    }

    public function getCount()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $query = UserCard::find()->where(['store_id' => $this->store_id, 'is_delete' => 0]);

        if ($this->user_id) {
            $query->andWhere(['user_id' => $this->user_id]);
        }

        $data = $query->select([
            'sum(case when is_use = 0 then 1 else 0 end) status_0',
            'sum(case when is_use = 1 then 1 else 0 end) status_1',
        ])->asArray()->one();
        return $data;
    }
}
