<?php
namespace app\modules\mch\models;

use app\models\RefundAddress;

/**
 * @property \app\models\RefundAddress $model;
 */
class RefundAddressForm extends MchModel
{
    public $store_id;
    public $model;

    public $name;
    public $address;
    public $mobile;
    public $is_delete;

    public function rules()
    {
        return [
            [['store_id'], 'integer'],
            [['name', 'address', 'mobile'], 'required'],
            [['name', 'address', 'mobile'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'name' => '姓名',
            'address' => '收货地址',
            'mobile' => '联系电话',
            'is_delete' => 'Is Delete',
        ];
    }
    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $this->model->store_id = $this->store_id;
        $this->model->name = $this->name;
        $this->model->address = $this->address;
        $this->model->mobile = $this->mobile;
        $this->model->is_delete = 0;

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
