<?php
namespace app\modules\mch\models\lottery;

use app\models\Goods;
use app\models\LotteryGoods;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;
use app\utils\TaskCreate;

class LotteryGoodsForm extends MchModel
{
    public $store_id;
    public $model;

    public $stock;
    public $status;
    public $sort;
    public $start_time;
    public $end_time;
    public $goods_id;
    public $attr;

    public function rules()
    {
        return [
            [['store_id', 'goods_id', 'start_time', 'end_time', 'stock' ,'status'], 'required'],
            [['store_id', 'goods_id', 'start_time', 'end_time', 'stock', 'sort', 'status'], 'integer'],
            [['attr'], 'required'],
            [['sort'], 'default', 'value' => 0],
            [['attr'], 'trim'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'goods_id' => '商品id',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'stock' => '数量',
            'attr' => '规格',
            'is_delete' => 'Is Delete',
            'sort' => '排序',
            'status' => '是否关闭',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
            'type' => '0未完成 1已完成',
        ];
    }

    public function search()
    {
        $query = LotteryGoods::find()
            ->where([
                'store_id' => $this->store_id,
                'is_delete' => 0
            ])->with(['goods' => function ($query) {
                $query->where([
                    'store_id' => $this->store_id,
                    'is_delete' => 0
                ]);
            }]);

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count]);

        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy(['type' => 0, 'create_time' => SORT_DESC, 'sort' => SORT_ASC])->asArray()->all();

        foreach ($list as $k => $v) {
            $attrs = json_decode($v['attr'], true);
            $name = '';
            foreach ($attrs as $v1) {
                $name .= $v1['attr_group_name'] . ':' . $v1['attr_name'] . ';';
            }
            $list[$k]['attrs'] = $name;
        }

        return [
            'list' => $list,
            'pagination' => $pagination
        ];
    }



    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        if ($this->start_time >= $this->end_time) {
            return [
                'code' => 1,
                'msg' => '时间错误'
            ];
        }

        $this->model->attributes = $this->attributes;
        $this->model->store_id = $this->store_id;
        $this->model->create_time = time();
        if ($this->model->save()) {
            //TaskCreate::lotteryGoods($this->model->id);
            return [
                'code' => 0,
                'msg' => '保存成功'
            ];
        } else {
            return $this->getErrorResponse($model);
        }
    }
}
