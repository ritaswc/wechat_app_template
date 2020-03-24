<?php
namespace app\modules\mch\models\step;

use app\models\Goods;
use app\models\StepGoods;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;

class StepGoodsForm extends MchModel
{
    public $store_id;
    public $model;

    public $status;
    public $sort;
    public $goods_id;
    public $attr;
    public $step_price;

    public function rules()
    {
        return [
            [['store_id', 'goods_id', 'status', 'sort', 'is_delete'], 'integer'],
            [['step_price'], 'number'],
            [['attr'], 'required'],
            [['sort'], 'default', 'value' => 100],
            [['attr'], 'trim'],

        ];
    }

    public function attributeLabels() 
    { 
        return [ 
            'id' => 'ID',
            'store_id' => 'Store ID',
            'goods_id' => 'Goods ID',
            'step_price' => '货币价',
            'create_time' => '创建时间',
            'attr' => '规格',
            'status' => '状态',
            'sort' => '排序',
            'is_delete' => 'Is Delete',
        ]; 
    }

    public function search()
    {

        $query = StepGoods::find()
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

        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy(['create_time' => SORT_DESC, 'id' => SORT_DESC])->asArray()->all();

        foreach($list as $k => $v){
            $attrs = json_decode($v['attr'],true);
            $name = '';
            foreach($attrs as $v1){
                $name .= $v1['attr_group_name'].':'.$v1['attr_name'].';';
            }
            $list[$k]['attrs'] = $name; 
        }

        return [
            'list'=>$list,
            'pagination'=>$pagination
        ];

    }



    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $this->model->attributes = $this->attributes;
        $this->model->store_id = $this->store_id;
        if ($this->model->save()) {
            return [
                'code' => 0,
                'msg' => '保存成功'
            ];
        } else {
            return $this->getErrorResponse($model);
        }
    }
}