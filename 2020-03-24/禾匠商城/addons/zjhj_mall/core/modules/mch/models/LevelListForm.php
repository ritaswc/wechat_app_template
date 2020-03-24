<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/2
 * Time: 14:01
 */

namespace app\modules\mch\models;

use app\models\Level;
use app\models\Model;
use yii\data\Pagination;

class LevelListForm extends MchModel
{
    public $store_id;


    public $page;
    public $limit;
    public $keyword;

    public function rules()
    {
        return [
            [['page'], 'default', 'value' => 1],
            [['limit'], 'default', 'value' => 20],
            [['keyword'], 'trim'],
            [['keyword'], 'string'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $query = Level::find()->where(['store_id' => $this->store_id, 'is_delete' => 0]);

        if ($this->keyword) {
            $query->andWhere(['like', 'name', $this->keyword]);
        }

        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);

        $list = $query->offset($p->offset)->limit($p->limit)->orderBy(['level' => SORT_ASC])->asArray()->all();
        return [
            'list' => $list,
            'p' => $p,
            'row_count' => $count
        ];
    }

    public function getAllLevel()
    {
        $list = Level::find()->where(['store_id' => $this->getCurrentStoreId(), 'is_delete' => Model::IS_DELETE_FALSE])
            ->select('id, level, name, discount')
            ->orderBy('level')
            ->asArray()->all();

        return $list;
    }
}
