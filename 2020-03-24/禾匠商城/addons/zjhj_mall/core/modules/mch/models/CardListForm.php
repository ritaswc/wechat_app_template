<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/27
 * Time: 16:06
 */

namespace app\modules\mch\models;

use app\models\Card;
use yii\data\Pagination;

class CardListForm extends MchModel
{
    public $store_id;

    public $page;
    public $limit;

    public function rules()
    {
        return [
            [['page'],'default','value'=>1],
            [['limit'],'default','value'=>20]
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $query = Card::find()->where(['is_delete'=>0,'store_id'=>$this->store_id]);

        $count = $query->count();

        $p = new Pagination(['totalCount'=>$count,'pageSize'=>$this->limit]);
        $list = $query->offset($p->offset)->limit($p->limit)->orderBy(['addtime'=>SORT_DESC])->asArray()->all();

        return [
            'list'=>$list,
            'row_count'=>$count,
            'pagination'=>$p
        ];
    }
}
