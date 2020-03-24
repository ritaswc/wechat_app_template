<?php
namespace app\modules\mch\models;

use app\models\Scratch;

/**
 * @property \app\models\Scratch $model;
 */
class ScratchForm extends MchModel
{
    public $store_id;
    public $model;


    public $type;
    public $num;
    public $price;
    public $stock;
    public $coupon_id;
    public $gift_id;
    public $create_time;
    public $update_time;
    public $attr;
    public $status;
    public $is_delete;

    public function rules()
    {
        return [
            [['store_id'], 'required'],
            [['store_id', 'type', 'num', 'stock', 'coupon_id', 'gift_id', 'create_time', 'update_time', 'status','is_delete'], 'integer'],
            [['num', 'stock'], 'integer','max'=>99999999],
            [['price'], 'number'],
            [['attr'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'type' => '1.红包2.优惠卷3.积分4.实物',
            'num' => '积分数量',
            'price' => '红包价格',
            'stock' => '库存',
            'coupon_id' => '优惠卷',
            'gift_id' => '赠品',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'attr' => '规格',
            'status' => '0关闭 1开启',
            'is_delete' => '23',
        ];
    }
    public function save()
    {

        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $this->model->store_id = $this->store_id;
        $this->model->type = $this->type;
        $this->model->stock = $this->stock;
        $this->model->create_time = time();
        $this->model->update_time = time();
        $this->model->status = $this->status;
        switch ($this->type) {
            case 1:
                $this->model->price = $this->price;
                break;
            case 2:
                $this->model->coupon_id = $this->coupon_id;
                break;
            case 3:
                $this->model->num = $this->num;
                break;
            case 4:
                $this->model->gift_id = $this->gift_id;
                $this->model->attr = $this->attr;
                break;
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
