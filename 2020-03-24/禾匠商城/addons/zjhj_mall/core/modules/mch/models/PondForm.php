<?php
namespace app\modules\mch\models;

use app\models\Pond;

/**
 * @property \app\models\Level $model;
 */
class PondForm extends MchModel
{
    public $store_id;
    public $model;

    public $type;
    public $num;
    public $price;
    public $image_url;
    public $stock;
    public $orderby;
    public $coupon_id;
    public $gift_id;
    public $attr;
    public $name;

    public function rules()
    {
        return [
            [['store_id'], 'required'],
            [['store_id', 'type', 'num', 'stock', 'orderby', 'coupon_id', 'gift_id'], 'integer'],
            [['price'], 'number'],
            [['attr'], 'string'],
            [['num', 'stock', 'orderby'], 'integer','max'=>99999999],
            [['image_url', 'name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'type' => '1.红包2.优惠卷3.积分4.实物.5.无',
            'num' => '积分数量',
            'price' => '红包价格',
            'image_url' => '图片',
            'stock' => '库存',
            'orderby' => '排序',
            'coupon_id' => '优惠卷',
            'gift_id' => '赠品',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'attr' => '规格',
            'name' => '别名'
        ];
    }
    public function save()
    {

        if (!$this->validate()) {
            return $this->errorResponse;
        }
        if($this->model->id){
            $this->model->update_time = time();
        }else{
            $this->model->create_time = time();
        }
        $this->model->store_id = $this->store_id;
        $this->model->orderby = $this->orderby;
        $this->model->image_url = $this->image_url;
        $this->model->type = $this->type;

        switch ($this->type) {
            case 1:
                $this->model->price = $this->price;
                $this->model->stock = $this->stock;
                $this->model->name = $this->name;
                break;
            case 2:
                $this->model->coupon_id = $this->coupon_id;
                $this->model->stock = $this->stock;
                break;
            case 3:
                $this->model->num = $this->num;
                $this->model->stock = $this->stock;
                $this->model->name = $this->name;
                break;
            case 4:
                $this->model->gift_id = $this->gift_id;
                $this->model->attr = $this->attr;
                $this->model->stock = $this->stock;
                break;
            case 5:
            default:
        }





        if ($this->model->save()) {
            return [
                'code'=>0,
                'msg'=>'保存成功'
            ];
        } else {
            return $this->getErrorResponse($this->model);
        }
    }
}
